<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" type="text/css" href="/css/style.css"/>
		<script src="/js/adminConfirm.js"></script>
		<script src="/js/adminSettings.js"></script>
		<title>Amenic - AdminsSettings</title>
	</head>
	<body>
		<div class="container">
			<div class="menuBar">
				<a href="/HomeController"><img src="/assets/MoviesPage/imgs/logo.svg" class="logo" alt="Amenic" /></a>
				<?php 
					switch($userType)
					{
						case "Admin":
							include 'SideMenus/AdminSideMenu.php';
							break;	
						case "Cinema":
							include 'SideMenus/CinemaSideMenu.php';
							break;	
						case "RUser":
							include 'SideMenus/RUserSideMenu.php';
							break;	
						case "Worker":
							include 'SideMenus/WorkerSideMenu.php';
							break;	
					}
				?>
				<a href="#"
					><div class="icon baseline">
						<svg
							width="48"
							height="48"
							viewBox="0 0 512 512"
							xmlns="http://www.w3.org/2000/svg"
						>
							<path
								d="m256 133.61c-67.484 0-122.39 54.906-122.39 122.39s54.906 122.39 122.39 122.39 122.39-54.906 122.39-122.39-54.906-122.39-122.39-122.39zm0 214.18c-50.613 0-91.793-41.18-91.793-91.793s41.18-91.793 91.793-91.793 91.793 41.18 91.793 91.793-41.18 91.793-91.793 91.793z"
							/>
							<path
								d="m499.95 197.7-39.352-8.5547c-3.4219-10.477-7.6602-20.695-12.664-30.539l21.785-33.887c3.8906-6.0547 3.0352-14.004-2.0508-19.09l-61.305-61.305c-5.0859-5.0859-13.035-5.9414-19.09-2.0508l-33.887 21.785c-9.8438-5.0039-20.062-9.2422-30.539-12.664l-8.5547-39.352c-1.5273-7.0312-7.7539-12.047-14.949-12.047h-86.695c-7.1953 0-13.422 5.0156-14.949 12.047l-8.5547 39.352c-10.477 3.4219-20.695 7.6602-30.539 12.664l-33.887-21.785c-6.0547-3.8906-14.004-3.0352-19.09 2.0508l-61.305 61.305c-5.0859 5.0859-5.9414 13.035-2.0508 19.09l21.785 33.887c-5.0039 9.8438-9.2422 20.062-12.664 30.539l-39.352 8.5547c-7.0312 1.5312-12.047 7.7539-12.047 14.949v86.695c0 7.1953 5.0156 13.418 12.047 14.949l39.352 8.5547c3.4219 10.477 7.6602 20.695 12.664 30.539l-21.785 33.887c-3.8906 6.0547-3.0352 14.004 2.0508 19.09l61.305 61.305c5.0859 5.0859 13.035 5.9414 19.09 2.0508l33.887-21.785c9.8438 5.0039 20.062 9.2422 30.539 12.664l8.5547 39.352c1.5273 7.0312 7.7539 12.047 14.949 12.047h86.695c7.1953 0 13.422-5.0156 14.949-12.047l8.5547-39.352c10.477-3.4219 20.695-7.6602 30.539-12.664l33.887 21.785c6.0547 3.8906 14.004 3.0391 19.09-2.0508l61.305-61.305c5.0859-5.0859 5.9414-13.035 2.0508-19.09l-21.785-33.887c5.0039-9.8438 9.2422-20.062 12.664-30.539l39.352-8.5547c7.0312-1.5312 12.047-7.7539 12.047-14.949v-86.695c0-7.1953-5.0156-13.418-12.047-14.949zm-18.551 89.312-36.082 7.8438c-5.543 1.207-9.9648 5.3789-11.488 10.84-3.9648 14.223-9.668 27.977-16.949 40.879-2.7891 4.9414-2.6172 11.02 0.45313 15.793l19.98 31.078-43.863 43.867-31.082-19.98c-4.7734-3.0703-10.852-3.2422-15.789-0.45313-12.906 7.2812-26.66 12.984-40.883 16.949-5.4609 1.5234-9.6328 5.9453-10.84 11.488l-7.8438 36.082h-62.031l-7.8438-36.082c-1.207-5.543-5.3789-9.9648-10.84-11.488-14.223-3.9648-27.977-9.668-40.879-16.949-4.9414-2.7891-11.02-2.6133-15.793 0.45313l-31.078 19.98-43.863-43.867 19.977-31.078c3.0703-4.7734 3.2461-10.852 0.45703-15.793-7.2812-12.902-12.984-26.656-16.953-40.879-1.5234-5.4609-5.9414-9.6328-11.484-10.84l-36.086-7.8438v-62.031l36.082-7.8438c5.543-1.207 9.9648-5.3789 11.488-10.84 3.9648-14.223 9.668-27.977 16.949-40.879 2.7891-4.9414 2.6172-11.02-0.45313-15.793l-19.98-31.078 43.863-43.867 31.082 19.98c4.7734 3.0703 10.852 3.2422 15.789 0.45313 12.906-7.2812 26.66-12.984 40.883-16.949 5.4609-1.5234 9.6328-5.9453 10.84-11.488l7.8438-36.082h62.031l7.8438 36.082c1.207 5.543 5.3789 9.9648 10.84 11.488 14.223 3.9648 27.977 9.668 40.879 16.949 4.9414 2.7891 11.02 2.6133 15.793-0.45313l31.078-19.98 43.863 43.867-19.977 31.078c-3.0703 4.7734-3.2461 10.852-0.45703 15.793 7.2852 12.902 12.984 26.656 16.953 40.879 1.5234 5.4609 5.9414 9.6328 11.484 10.84l36.086 7.8438z"
							/>
						</svg>
					</div>
					Settings</a
				>
			</div>
			<div class="adminWrapper">
			<div class="topBar">
					<div></div>
					<div class="user">
						<img
						src="<?php if(!$token->image) echo"/assets/Admins/profile.jpeg"; else echo "data:image/jpg;base64, ".$token->image;  ?>"
						class="profPic"
						alt="Profile picture"
						/>
						<span>
							<?php echo $token->firstName." ".$token->lastName?>
						</span>
					</div>
				</div>	
				<?php
					//fetching data
					$nameError = isset($errors['name']) && !is_null($errors['name']) ? $errors['name'] : "";
					$fNameError = isset($errors['fName']) && !is_null($errors['fName']) ? $errors['fName'] : ""; 
					$lNameError = isset($errors['lName']) && !is_null($errors['lName']) ? $errors['lName'] : ""; 
					$emailError = isset($errors['email']) && !is_null($errors['email']) ? $errors['email'] : ""; 
					$countryError = isset($errors['country']) && !is_null($errors['country']) ? $errors['country'] : ""; 
					$cityError = isset($errors['city']) && !is_null($errors['city']) ? $errors['city'] : "";
					$phoneError = isset($errors['phone']) && !is_null($errors['phone']) ? $errors['phone'] : ""; 
					$addressError = isset($errors['address']) && !is_null($errors['address']) ? $errors['address'] : ""; 
					$pswdError = isset($errors['pswd']) && !is_null($errors['pswd']) ? $errors['pswd'] : ""; 
					$imgError = isset($errors['profilePicture']) && !is_null($errors['profilePicture']) ? $errors['profilePicture'] : ""; 
				?>
                <form enctype="multipart/form-data" action="saveSettings" class="searchForm" method="POST">
                    <div class="settingsForm">
                            <div class="span2">
								<h2><?php
										switch($userType)
										{
											case "Admin":
												echo "Admin info";
												break;	
											case "Cinema":
												echo "Cinema info";
												break;	
											case "RUser":
												echo "RUser info";
												break;	
											case "Worker":
												echo "Worker info";
												break;	
										}
									?>
								</h2>
                            </div>
                            <div class="adminPicture">
								<img src="<?php 
										$image = is_null($image) ? "/assets/Admins/profile.jpeg" : "data:image/jpg;base64, ".$image;
										echo $image; 						
									?>" id="adminPic" alt="Picture not found!" />
                                <label class="browseButton">
                                    <input type="file" onchange="showPicture()" id="profilePicture" name="profilePicture"/>    
                                    Browse
                                </label>
								<div class="formError ml-1"><?php echo $imgError;?></div>
							</div>
								<?php
									//firstName and lastName row
									if(strcmp($userType,"Cinema") == 0)
									{
										echo "
											<div class=\"span2Make3\">
												<div class=\"span2\">
													<label for=\"name\" >Name</label><br>
													<input type=\"text\" class=\"whole\"id=\"name\" name=\"name\" value=\"".$data['firstName']."\"><br>
													<div class=\"formError ml-1\">".$nameError."</div>
												</div>
												<div></div>
											</div>
												</div>
												<div></div>
											</div>
											";
									}
									else
									{
										echo "
										<div class=\"span2Make2\">
											<div>
												<label for=\"fName\">First name</label><br>
												<input type=\"text\" id=\"fName\" name=\"fName\" value=\"".$data['firstName']."\"><br>
												<div class=\"formError ml-1\">".$fNameError."</div>
											</div>
											<div>
												<label for=\"lName\">Last name</label><br>
												<input type=\"text\" id=\"lName\" name=\"lName\" value=\"".$data['lastName']."\"><br>
												<div class=\"formError ml-1\">".$lNameError."</div>
											</div>
										</div>
										";
									}
									
									//emailRow
									if (strcmp($userType,"Cinema") != 0)
									{
										echo "
											<div class=\"span3Make4\">
												<div class=\"adminEmail span2\">
													<label for=\"email\">Email</label><br>
													<input type=\"text\" id=\"email\" name=\"email\" value=\"".$data['email']."\" readonly><br>
													<div class=\"formError ml-1\">".$emailError."</div>
												</div>
												<div class=\"span2 phoneSettings\">";
										if (strcmp($userType,"RUser") == 0)
										{
											echo"
												<label for=\"phone\">Phone number (optional)</label><br>
												<input type=\"text\" id=\"phone\" name=\"phone\" value=\"".$data['phone']."\"><br>
											";
										}
										echo "</div> </div>";
									}

									//countryCity row
									if (strcmp($userType,"Cinema") == 0 || strcmp($userType,"RUser") == 0)
									{
										echo "
											<div class=\"span3Make4\">
												<div>	
													<label for=\"country\">Country</label>
													<select class=\"formSelect settingsSelect\" name=\"country\" ";
													
										if (strcmp($userType,"Cinema") == 0) 
												echo "required";
										echo "		><option value=\"1\">Serbia</option>
													</select>
													<div class=\"formError ml-1\">".$countryError."</div>
												</div>
												<div>	
													<label for=\"country\">City</label>
													<select class=\"formSelect settingsSelect\" name=\"city\" ";
										if (strcmp($userType,"Cinema") == 0) 
											echo "required";
										echo " 		><option value=\"1\">Beograd</option>
													<option value=\"2\">Novi Sad</option>
													<option value=\"3\">Niš</option>
													</select>
													<div class=\"formError ml-1\">".$cityError."</div>
												</div>
												<div class=\"span2\"></div>
											</div>
											";
									}

									//phoneAdress row
									if (strcmp($userType,"Cinema") == 0)
									{
										echo "
											<div class=\"span3Make4\">
												<div>	
													<label for=\"phone\">Phone</label><br>
													<input type=\"text\" id=\"phone\" name=\"phone\" value=\"".$data['phone']."\"><br>
													<div class=\"formError ml-1\">".$phoneError."</div>
												</div>
												<div>	
													<label for=\"address\">Address</label><br>
													<input type=\"text\" id=\"address\" name=\"address\" value=\"".$data['address']."\"><br>
													<div class=\"formError ml-1\">".$addressError."</div>
												</div>
												<div class=\"span2\"></div>
											</div>
											";
									}
							?>
                            <div class="passwordRow">
                                <div class="span2">
                                    <label for="pswdOld">Old password</label><br>
									<input class="settingsInputText" type="password" id="pswdOld" name="pswdOld"><br>
                                </div>
                                <div class="span2">
                                    <label for="password">New password</label><br>
									<input class="settingsInputText" type="password" id="password" name="pswdNew"><br>
                                </div>
                            </div>
							<div class="span4 formError ml-1"><?php echo $pswdError;?></div>
                            <div class="passwordRow">
                                <div class="row centerY mb-2">
									<span id="strengthBarTitle">Strength: </span>
									<span id="strengthBar1" class="strengthBar mr-1 ml-2"></span>
									<span id="strengthBar2" class="strengthBar mr-1"></span>
									<span id="strengthBar3" class="strengthBar mr-1"></span>
									<span id="strengthBar4" class="strengthBar"></span>
                                </div>
                                <div class="requestSettingsButtons">
                                	<button class="requestApproveButton saveButton">Save changes</button>
                                	<input type="hidden" id="actMenu" name="actMenu" value="<?php echo $actMenu; ?>">
                            	</div>
                            </div>
                    </div>
                </form> 
			</div>
		</div>
	</body>
	<script src="/js/passwordStrength/zxcvbn.js"></script>
    <script src="/js/passwordStrength/passwordStrength.js"></script>
</html>
