<?php if (!isset($_SESSION)) session_start(); ?>


<?php // Récupère les variables AJAX dans la variable $_POST au format convenu ?>

<?php  /* Renvoie une chaine de caractère au format var1=foo&var2=bar.
       L'ajax récupèrera le contenu intégral de ce qui est renvoyé,
       au foramt "Error XXX: description" si erreur ou "var1=foo&var2=bar" si ok.

       Ce fichier sert de boite de réception / d'envoi de la couche modèle : ne pas le renommer.
       Le contenu pourra être contenu dans des fichiers externes reliés par un include().
       */
       ?>
