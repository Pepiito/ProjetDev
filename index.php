<?php if (!isset($_SESSION)) session_start(); ?>
<?php include("./php/vue/head.php"); ?>
  <body>
    <div id="background"></div>
    <div id="background2"></div>
    <header>
      <span id="titre">
        Circé en plus classe
      </span>
    </header>
    <div id="contenu">
      <?php for ($i = 0; $i<=20; $i++) {
        echo "<button type=button name=button>Button $i</button>";
      } ?>
    </div>
    <footer>
    	<div id="logo_ensg">
    		<img src="./images/logo_ensg.png" alt="Logo ENSG">
    	</div>
    	<div id="postdata">
    		<p>Tous droits réservés.</p>
    		<p>Ceci est une application à but non commercial, réalisée dans le cadre du projet développement de 2ème année à l'ENSG et de 3ème année à l'HEIVGp>
    		<p>Pour toutes questions, contactez-nous aux adresses suivantes: <strong>benoit.messiaen@ensg.eu</strong> ou <strong>hugo.lecomte@ensg.eu</strong></p>
    	  <p><em>ENSG Géomatique, 6-8 avenue Blaise Pascal, Cité Descartes, 77420 Champs-sur-Marne</em></p>
      </div>
      <div id="logo_heig">
    		<img src="./images/heig.png" alt="Logo HEIG">
    	</div>
    </footer>
    <script type="text/javascript" src="./js/controleur.js"></script>
  </body>
</html>
