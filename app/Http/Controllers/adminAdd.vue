<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue'


const token = typeof localStorage !== 'undefined' ? localStorage.getItem('token') : null;

definePageMeta({
  layout: "admin",
});
const selectedGareId = ref(null);
const garess = ref([]);
const user = ref(null)
const route = useRoute()

const getUser = async (userId) => {
  const axios = useNuxtApp().$axios
  const token = useCookie('token', {
    watch: true,
    httpOnly: true
  })

  const getUserUrl = `https://api.srtk.ics-tn.com/api/admin/user/${userId}`

  try {
    const response = await axios.get(getUserUrl, {
      headers: { Authorization: `Bearer ${token.value}` }
    })

    user.value = response.data // Assuming the response contains the user object
  } catch (error) {
    //console.error("Error retrieving user:", error)
    // Handle the error scenario, display an error message, etc.
  }
}

onMounted(async () => {
  const userId = route.query.id
  if (userId) {
    await getUser(userId)
  }
})



const headers = {
  Authorization: `Bearer ${token}`,
  'Content-Type': 'multipart/form-data',
};
const name = ref('');
const email = ref('');
const password = ref('');
const password_confirmation = ref('');

const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const snackbar = ref({
  show: false,
  message: '',
  color: '',
});
const resetForm = () => {
  name.value = '';
  email.value = '';
  password.value = '';
  password_confirmation.value = '';
  //selectedGareId = ref(null);
};
const handleSubmit = async () => {
  if (!name.value || !email.value || !password.value || !password_confirmation.value) {
    snackbar.value = {
      show: true,
      message: "Veuillez remplir tous les champs!",
      color: "error",
      position: 'top'
    };
    return;
  }
  if (!emailRegex.test(email.value)) {
    snackbar.value = {
      show: true,
      message: "Email is not valid!",
      color: "error",
      position: 'top'
    };
    resetForm();
    return;
  }

  if (password.value.length < 8) {
    snackbar.value = {
      show: true,
      message: "le mot de passe doit contenir au moins 8 caractères",
      color: "error",
    };
    resetForm();
    return;
  }

  if (password.value !== password_confirmation.value) {
    snackbar.value = {
      show: true,
      message: "les mots de passe ne correspondent pas!",
      color: "error",
    };
    resetForm();
    return;
  }

  const payload = {
    name: name.value,
    email: email.value,
    password: password.value,
    password_confirmation: password_confirmation.value,
    gare_id: selectedGareId.value
  };

  try {
    const response = await axios.post('https://api.srtk.ics-tn.com/api/admin/register', payload, {
      headers: headers
    });
    if (response.status === 200) {
      ////console.log("hello");
      snackbar.value = {
        show: true,
        message: 'Inscription réussie!',
        color: 'success',
      };

      resetForm();
    }
    else {
      ////console.log("hna");
      snackbar.value = {
        show: true,
        message: 'Échec de l\'inscription!',
        color: 'error',
      };
    }
  } catch (error) {
    ////console.log("error");
    //console.error(error);
    snackbar.value = {
      show: true,
      message: 'probleme insertion!',
      color: 'error',
    };
  }
};



// Fetch gares
async function fetchgares() {
  try {
    const response = await axios.get('https://api.srtk.ics-tn.com/api/gares');
    return garess.value = response.data.Gares.data;
  } catch (error) {
    //console.error('Error fetching gares:', error);
  }
}

onMounted(() => {
  // Check if the admin is logged in
  fetchgares();
  if (!token) {
    // Redirect to the login page or perform any desired action
    window.location.href = '/';
  } else {
    headers.Authorization = `Bearer ${token}`;
  }
});
</script>
<template>
  <div :class="colorMode">
    <v-container>
      <v-form @submit.prevent="handleSubmit">
        <v-snackbar v-model="snackbar.show" :color="snackbar.color" top>
          {{ snackbar.message }}
        </v-snackbar>
        <v-row>
          <v-col cols="12" md="6">
            <v-text-field v-model="name" label="Nom" placeholder="Nom" class="input-field">
              <template #label>
                <span class="custom-label">Nom:</span>
              </template>
            </v-text-field>
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field v-model="email" label="Email" type="email" placeholder="Email" class="input-field">
              <template #label>
                <span class="custom-label">Email:</span>
              </template>
            </v-text-field>
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field v-model="password" label="Mot de passe" type="password" placeholder="Mot de passe"
              class="input-field">
              <template #label>
                <span class="custom-label">Mot de passe:</span>
              </template>
            </v-text-field>
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field v-model="password_confirmation" label="Confirmer le Mot de passe" type="password"
              placeholder="Confirmer le Mot de passe" class="input-field">
              <template #label>
                <span class="custom-label">Confirmer le Mot de passe:</span>
              </template>
            </v-text-field>
          </v-col>
          <v-col cols="6" sm="6">
            <select v-model="selectedGareId" class="forms" autocomplete>
              <option value="null">Sélectionnez une région</option>
              <option v-for="gare in garess" :value="gare.id">{{ gare.name }}</option>
            </select>
          </v-col>
          <v-col cols="12">
            <v-btn type="submit" color="green" class="me-2" rounded icon>
              <v-icon>mdi-check</v-icon>
            </v-btn>
            <v-btn color="blue" type="reset" variant="tonal" rounded icon @click="resetForm">
              <v-icon>mdi-refresh</v-icon>
            </v-btn>
          </v-col>
        </v-row>
      </v-form>
    </v-container>
  </div>
</template>

<style lang="scss" scoped>
.custom-label {
  font-weight: bold;
  color: blue;
  /* Add any other custom label styles you want */
}

.input-field {
  width: 100%;
  max-width: 400px;
  /* Adjust the maximum width as needed */
}

.dark {
  background-color: #333;
  color: #fff;
}

.normal {
  background-color: #fff;
  color: #333;
}

@media (max-width: 600px) {
  .input-field {
    max-width: 100%;
    /* Set full width on smaller screens */
  }
}
</style>
