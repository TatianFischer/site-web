$(document).ready(function(){

	// Traitement des suggestions de recherche
	$('#searchInput').on("keyup", function(event){
		var min_lenght = 2;
		var keyword = $(this).val();

		if(keyword.length >= min_lenght){
			$.ajax({
				method : $('#searchForm').attr('method'),
				url : 'admin/traitement_ajax.php',
				data : {'search' : keyword},
				dataType : 'json',
			})
			.done(function(produits){
				console.log(produits);
				$('.product_list').empty().show();
				$.each(produits, function(index, produit){
					console.log(produit.titre);
					$('<li>')
						.append($('<a>').attr('href', 'fiche_produit.php?id='+produit.id_produit).text(produit.titre))
						.appendTo('.product_list');
				})
				
			})

			.fail(function(result, status, error){
                console.log("Réponse jQuery : " + result);
                console.log("Statut de la requète : " + status);
                console.log("Type d’erreur : " + error);
                console.log(result);
            })
			
		} else {
			$(".product_list").empty().hide();
		}
	})
})