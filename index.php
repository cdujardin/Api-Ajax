<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8"/>
		<title>API Rest Ajax</title>
		<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
		<link rel="stylesheet" type="text/css" href="style.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
		<script>
		var addUser = function() {
			if(jQuery("#pet:checked").val() == 1) {
				var hasPet = true;
			}
			else {
				hasPet = false;
			}
			jQuery.ajax({
				type: 'POST',
				url: 'http://tp-lens.pary.fr/api/users/',
				data: {
					firstname:jQuery("#firstname").val(),
					lastname: jQuery("#lastname").val(),
					email: jQuery("#email").val(),
					birthday: jQuery("#birthday").val(),
					github: jQuery("#github").val(),
					sex: jQuery("[name='sex']:checked").val(),
					pet: hasPet,
				},
				crossDomain: true,
				complete: function(rawResponse) {
					console.log("Raw response: ");
					console.log(rawResponse);
					loader();
				}
			});
			alert("Données bien ajoutées.");

		};

		var userDetail = funstion(userId){
			jQuery.ajax({
				type: 'GET',
				url: 'http://tp-lens.popschool.fr/api/users/' + userI.data,,
				crossDomain: true,
				complete: userList,
				function(r) {
					console.log(r);
					user = r.responseJSON:
					console.log(user);
					jQuery("#user_detail").html("<div>"  user.id + " . " +  user.firstname + " " + user.lastname + "</div>");
					jQuery("#user_detail").append("<div id='user_birthday'>"  user.birthday  "</div>");
				}
			});
		}

		var removeUser = function() {
			        jQuery.ajax({
				        type: 'DELETE',
				        url: 'http://tp-lens.pary.fr/api/users/$id',
				        data: {
					        id:jQuery("#idefface").val(),
				        },

				        crossDomain: true,
				        complete: function(rawResponse) {
					        console.log("Raw response: ");
					        console.log(rawResponse);
					        loader();
				        }
			        });
			        alert("Dernières données effacées.");
		        };


		// var reset = function() {
		// 	if(jQuery("#pet:checked").val() == 1) {
		// 		var hasPet = true;
		// 	}
		// 	else {
		// 		hasPet = false;
		// 	}
		// 	jQuery.ajax({
                    //                                     type: 'DELETE',
                    //                                     url: 'http://tp-lens.pary.fr/api/users/',
		// 		data: {
		// 			firstname:jQuery("#firstname").val(),
		// 			lastname: jQuery("#lastname").val(),
		// 			email: jQuery("#email").val(),
		// 			birthday: jQuery("#birthday").val(),
		// 			github: jQuery("#github").val(),
		// 			sex: jQuery("[name='sex']:checked").val(),
		// 			pet: hasPet,
		// 		},
                    //                                     crossDomain: true,
                    //                                     complete: function(rawResponse) {
                    //                                                   console.log("Raw response: ");
                    //                                                   console.log(rawResponse);
		// 			loader();
                    //                                     }
                    //                       });
		// };

		var loader = function() {
                                          jQuery.ajax({
                                                        type: 'GET',
                                                        url: 'http://tp-lens.popschool.fr/api/users/',
                                                        crossDomain: true,
                                                        complete: function(rawResponse) {
                                                                      // console.log("Raw response: ");
                                                                      // console.log(rawResponse);
                                                                      users = rawResponse.responseJSON;
                                                                      // console.log("Users: ");
                                                                      // console.log(users);
					jQuery("#result").html("<h2>Les Users sont : </h2>");
					users.forEach(function(user) {
                                                                                    // console.log("User: " + user.id);
                                                                                    // console.log(user);
						jQuery("#result").append("<div id='user" + user.id + "'>" + user.id + " . " +  user.firstname + " " + user.lastname + "</div>");
                                                                      });
                                                        }
                                          });
		};

                            jQuery('document').ready(loader);
                            </script>
	</head>
	<body>
		<h1>API Rest : Users</h1>
		<center>
			<div class="container">
				<div class="row">
					<div id="result" class="col-md-5 col-xs-12"></div>
					<div id="ajout" class="col-md-6 col-xs-12">
						<h2> Ajouter un User : </h2>
						<form>
							<input type='text' maxlength='200' placeholder='firstname'  id='firstname' name='firstname' required>
							<input type='text' maxlength='200' placeholder='lastname' id='lastname' required/></br>
							<input type='email' maxlength='400' placeholder='email' id='email'/></br>
							<input type='date' placeholder='birthday' id='birthday'/></br>
							<input type='text' maxlength='200' placeholder='github' id='github'/></br>
							<input type='radio' name="sex" value='H' required/>Homme
							<input type='radio' name="sex" value='F' required/>Femme</br>
							<input type='checkbox' id='pet' value="1" />Avez vous des animaux?</br>
							<input class="btn btn-default" type='button' onclick="addUser();" value='Add user'/>
							<input class="btn btn-default" type='button' onclick="reset();" value='Reset'/>
						</form>
					</div>
				</div>
				<div class="row">
					<div id="remove"  class="col-md-6 col-xs-12">
						<h2> Effacer un User :</h2>
						<form>
							<input type='number' maxlength='200' placeholder='id à effacer'  id='idefface' name='idefface' required>
							<input class="btn btn-default" type='button' onclick="removeUser();" value='Remote user'/>
						</form>
					</div>
				</div>
			</div>

		</center>



	</body>
</html>
