<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <style>
        select {
            width: 100%;
            padding: 8px;
            margin-top: 10px;
        }

        .ss {
            width: 100%;
            padding: 8px;
            margin-top: 10px;
        }

        .bt {
            padding: 8px;
            margin: 0 auto;
            margin-top: 8px;
            width: 50%;
            background-color: rgb(58, 133, 255);
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
    </style>
</head>

<body class="antialiased">
    <?php
    $gares = DB::table('gares')
        ->join('gare_translations', 'gares.id', '=', 'gare_translations.gare_id')
        ->select('gares.id', 'gare_translations.name')
        ->where('gare_translations.locale', '=', 'ar')
        ->get();
    $typesPaiement = DB::table('abonnes')
        ->where('type_paiment', '!=', '')
        ->distinct()
        ->pluck('type_paiment');
    $typpers = DB::table('abonnes')
        ->where('type_eleve', '!=', '')
        ->distinct()
        ->pluck('type_eleve');
    
    ?>
    <form action="/api/imp_abn" method="POST">
        <select name="gare_id" id="" required>
            <option value=""> select gare </option>
            <?php foreach($gares as $g){?>
            <option value="<?= $g->id ?>"><?= $g->name ?> </option>
            <?php } ?>
        </select>

        <select name="typespaiement" id="" required>
            <option value=""> type Abonnement </option>
            <?php foreach($typesPaiement as $g){?>
            <option value="<?= $g ?>"><?= $g ?> </option>
            <?php } ?>
        </select>
        <select name="typpers" id="" required>
            <option value=""> type Abonnement </option>
            <?php foreach($typpers as $g){?>
            <option value="<?= $g ?>"><?= $g ?> </option>
            <?php } ?>
        </select>
        <input type="text" name='date' class="ss">

        <input type="submit" class="bt" value="imprimer">
    </form>
</body>

</html>
