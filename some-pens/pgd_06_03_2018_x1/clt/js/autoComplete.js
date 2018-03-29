		function autoCompletee($obj,$nomTable,$idTable,$labelTable,$cible){
				alert("mmm");
				var sourceUrl = "../autoComplete.php?nomTable="+$nomTable+"&nomId="+$idTable+"&nomAff="+$labelTable;
					
				   $obj.autocomplete({
						source: sourceUrl,
						minLength: 2,
						select: function(event, ui) {
							$cible.val(ui.item.id);
							$obj.val(ui.item.label);
						}
					});
				
		}