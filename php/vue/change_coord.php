<div id="popup" class="modal">
	<div class="modal-content">
		<span class="close">&times;</span>
		<div id="pop_body">
		<div id="pop_header">
			<p id="head1" onclick="show_trans_coord()">Transformation de coordonnées</p>
			<p id="head2" onclick="show_trans_fichier()">Transformation via fichier</p>
		</div>
		<div id="trans_coord">
			<p>essai_coord</p>
		</div>
		<div id="trans_fichier">
			<p>essai_fichier</p>
		</div>
		
		
		</div>
		
		
	</div>
</div>
<script type="text/javascript">
var body_trans1 = document.getElementById('trans_coord');
var body_trans2 = document.getElementById('trans_fichier');
var head_trans1 = document.getElementById('head1');
var head_trans2 = document.getElementById('head2');

function show_trans_coord() {
	body_trans1.style.display="block";
	body_trans2.style.display="none";
	head_trans1.style.borderWidth="0px 1px 0px 0px";
	head_trans2.style.borderWidth="0px 0px 2px 1px";
}
function show_trans_fichier() {
	body_trans2.style.display="block";
	body_trans1.style.display="none";
	head_trans1.style.borderWidth="0px 1px 2px 0px";
	head_trans2.style.borderWidth="0px 0px 0px 1px";
}
</script>