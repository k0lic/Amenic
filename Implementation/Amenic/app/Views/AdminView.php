<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" type="text/css" href="/css/style.css"/>
		<script src="/js/adminConfirm.js"></script>
		<title>Amenic - Admins</title>
	</head>
	<body>
		<div class="container">
			<div class="menuBar">
				<a href="/HomeController"><img src="/assets/MoviesPage/imgs/logo.svg" class="logo" alt="Amenic" /></a>
				<?php include 'SideMenus/AdminSideMenu.php'; ?>
				<a href="/AdminController/settings" class="<?php if(strcmp($actMenu,"5")==0) echo "activeMenu";?>"
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
			<div class="modalWrapper" id="deleteModalWrapper">
				<form action="/AdminController/removeUser" method="POST" id="deleteModalForm">	
					<div class="modal centerX" id="deleteModal">
						<div class="modalHead">Are you sure?</div>
						<span>You're about to delete this user. </span>
						<div class="confirmModuleButtons">
								<button>Yes</button>
								<button class="transparentBg" onclick="hideModal('deleteModalWrapper')" formaction="javascript:void(0);">No</button>
						</div>
					</div>
				</form>	
			</div>
			<div class="modalWrapper" id="addAdminWrapper" style="<?php if (isset($errors)) echo "display: block;"; else echo "display: none;";?>">
				<form action="/AdminController/addAdmin" method="POST" id="addAdminForm">
					<div class="modal centerX" id="addAdminModal">
							<div class="modalHead">Add admin</div>	
							<div class="addAdminModalColumn">
								<div class="modalColumn">
									<!--first name new admin-->
									<label for="fNameNA">First name</label>
									<input type="text" id="fNameNA" name="fNameNA" value="<?php if(isset($form['fNameNA'])) echo $form['fNameNA'] ?>"/>
									<div class="formError ml-1"><?php if(isset($errors['fNameNA'])) echo $errors['fNameNA'] ?></div>
								</div>
								<div class="modalColumn">
									<label for="lNameNA">Last name</label>
									<input type="text" id="lNameNA" name="lNameNA" value="<?php if(isset($form['lNameNA'])) echo $form['lNameNA']; ?>"/>
									<div class="formError ml-1"><?php if(isset($errors['lNameNA'])) echo $errors['lNameNA'] ?></div>
								</div>
							</div>
							<div class="modalColumn mt-2">
								<label for="emailNA">Email</label>
								<input type="text" id="emailNA" name="emailNA" value="<?php if(isset($form['emailNA'])) echo $form['emailNA']; ?>"/>
								<div class="formError ml-1"><?php if(isset($errors['emailNA'])) echo $errors['emailNA'] ?></div>
							</div>
							<div class="modalColumn mt-2">
								<label for="password">Password</label>
								<input type="password" id="password" name="passwordNA" />
								<div class="formError ml-1"><?php if(isset($errors['passwordNA'])) echo $errors['passwordNA'] ?></div>
							</div>
							<div class="modalColumn mt-2">
								<label for="passwordConfirmNA">Confirm password</label>
								<input type="password" id="passwordConfirmNA" name="passwordConfirmNA" />
								<div class="formError ml-1"><?php if(isset($errors['passwordConfirmNA'])) echo $errors['passwordConfirmNA'] ?></div>
							</div>
							<div class="row centerY mb-2">
								<span id="strengthBarTitle">Strength: </span>
								<span id="strengthBar1" class="strengthBar mr-1 ml-2"></span>
								<span id="strengthBar2" class="strengthBar mr-1"></span>
								<span id="strengthBar3" class="strengthBar mr-1"></span>
								<span id="strengthBar4" class="strengthBar"></span>
							</div>
							<div class="confirmModuleButtons">
									<button>Yes</button>
									<button class="transparentBg" onclick="hideModal('addAdminWrapper')" formaction="javascript:void(0);">No</button>
							</div>
					</div>
				</form>	
			</div>
			<div class="adminWrapper">
				<div class="topBar">
					<form action="javascript:void(0);" method="POST" class="searchForm">
						<label>
							<input
								id="searchBar"
								type="text"
								placeholder="Search"
								class="search"
								name="phrase"
								value="<?php if (isset($phrase))
												echo $phrase;
											 else 
											 	echo '';
									?>"
							/>
						</label>
						<input type="hidden" id="actMenu" name="actMenu" value="<?php echo $actMenu;?>" />	 
					</form>
					<div>
						<button class="addAdminButton" onclick=showSpecModal('addAdminWrapper')>
							Add admin
						</button>
					</div>
					<div class="user">
						<img
						src="<?php if(!$token->image) echo"/assets/profPic.png"; else echo "data:image/jpg;base64, ".$token->image;  ?>"
						class="profPic"
						alt="Profile picture"
						/>
						<span>
							<?php echo $token->firstName." ".$token->lastName?>
						</span>
					</div>
				</div>	
				<div class="list" id="list">
				<?php 
					foreach ($data as $row)
					{
						$city = !isset($row->cityName) || is_null($row->cityName) ? "" : " • ".$row->cityName;
						$country = !isset($row->countryName) || is_null($row->countryName) ? "" : " • ".$row->countryName;
						$image = !isset($row->image) || is_null($row->image) ? "/assets/profPic.png" : "data:image/jpg;base64, ".$row->image;
						$name = strcmp($actMenu,0)==0 || strcmp($actMenu,3) == 0 ? $row->firstName." ".$row->lastName : $row->name;
						$address = strcmp($actMenu,1)==0 || strcmp($actMenu,2)==0 ? " • ".$row->address : "";
						$phone = strcmp($actMenu,1)==0 || strcmp($actMenu,2)==0 ? " • ".$row->phoneNumber : "";
                            echo "<form action=\"javascript:void(0);\" method=\"POST\" class=\"rowWrapper\">							
									<div class=\"userPicture\">
                                            <img src=\"".$image."\" alt=\"\"/>
                                    </div>
                                    <div class=\"description\">
                                        <div><h1>".$name."</h1></div>     
										<div><span>".$row->email.$address.$city.$country.$phone."</span></div>
                                    </div>";
                            if($actMenu == 1)
                            {
								echo "<div class=\"editWrapper\">
										<button class=\"highlightSvgOnHover\" formaction=\"/AdminController/editRequest\">
											<svg viewBox=\"-264 116.7 469.3 469.3\">
												<path class=\"st0\" d=\"M192.8,192.8l-64-64.1c-16.1-16.1-44.2-16.2-60.4,0l-286.7,289.5c-1.3,1.3-2.2,2.9-2.7,4.6l-42.7,149.5
												c-1.1,3.7,0,7.7,2.7,10.5c2,2,4.8,3.1,7.5,3.1c1,0,2-0.1,2.9-0.4l149.3-42.7c1.7-0.5,3.3-1.4,4.6-2.7l289.3-287
												c8.1-8.1,12.5-18.8,12.5-30.2S200.9,200.9,192.8,192.8z M22,206.4l39.3,39.3l-205,205l-14.7-29.4c-1.8-3.6-5.5-5.9-9.5-5.9h-17.1
												L22,206.4z M-237.8,559.8l13.9-48.6l34.7,34.7L-237.8,559.8z M-114.7,524.6l-51,14.6l-51.5-51.5l14.6-51h28l18.4,36.8
												c1,2.1,2.7,3.7,4.8,4.8l36.8,18.4L-114.7,524.6L-114.7,524.6z M-93.3,507.1V490c0-4-2.3-7.7-5.9-9.5l-29.4-14.7l205-205l39.3,39.3
												L-93.3,507.1z M177.8,238.4l-47,46.6L37,191.3l46.6-47c8.1-8.1,22.1-8.1,30.2,0l64,64c4,4,6.3,9.4,6.3,15.1
												S181.8,234.4,177.8,238.4z\"/>
											</svg>
										</button>
										<input type=\"hidden\" name=\"key\" value=\"".$row->email."\" />
									  	<input type=\"hidden\" name=\"actMenu\" value=\"".$actMenu."\" />	 
                                      </div>";
							}
                            if($actMenu == 0 || $actMenu == 1)
                            {
								echo "<div class=\"binWrapper\">
										<button class=\"highlightSvgOnHover\" onclick=\"showModal('".$row->email."', '".$actMenu."')\" >
											<svg viewBox=\"-286 137 346.8 427\">
												<path d=\"M-53.6,291.7c-5.5,0-10,4.5-10,10v189c0,5.5,4.5,10,10,10s10-4.5,10-10v-189C-43.6,296.2-48.1,291.7-53.6,291.7 z\"></path>
												<path d=\"M-171.6,291.7c-5.5,0-10,4.5-10,10v189c0,5.5,4.5,10,10,10s10-4.5,10-10v-189 C-161.6,296.2-166.1,291.7-171.6,291.7z\"></path>
												<path d=\"M-257.6,264.1v246.4c0,14.6,5.3,28.2,14.7,38.1c9.3,9.8,22.2,15.4,35.7,15.4H-18c13.5,0,26.4-5.6,35.7-15.4 c9.3-9.8,14.7-23.5,14.7-38.1V264.1c18.5-4.9,30.6-22.8,28.1-41.9C58,203.2,41.8,189,22.6,189h-51.2v-12.5 c0.1-10.5-4.1-20.6-11.5-28c-7.4-7.4-17.6-11.6-28.1-11.5H-157c-10.5-0.1-20.6,4-28.1,11.5c-7.4,7.4-11.6,17.5-11.5,28V189h-51.2 c-19.2,0-35.4,14.2-37.9,33.3C-288.2,241.3-276.1,259.2-257.6,264.1z M-18,544h-189.2c-17.1,0-30.4-14.7-30.4-33.5V265h250v245.5 C12.4,529.3-0.9,544-18,544z M-176.6,176.5c-0.1-5.2,2-10.2,5.7-13.9c3.7-3.7,8.7-5.7,13.9-5.6h88.8c5.2-0.1,10.2,1.9,13.9,5.6 c3.7,3.7,5.7,8.7,5.7,13.9V189h-128V176.5z M-247.8,209H22.6c9.9,0,18,8.1,18,18s-8.1,18-18,18h-270.4c-9.9,0-18-8.1-18-18 S-257.7,209-247.8,209z\"></path>
												<path d=\"M-112.6,291.7c-5.5,0-10,4.5-10,10v189c0,5.5,4.5,10,10,10s10-4.5,10-10v-189 C-102.6,296.2-107.1,291.7-112.6,291.7z\"></path>
											</svg>
										</button>								
									  </div>";
									  //<img src=\"/assets/Admins/bin.svg\" alt=\"Img error!\"/>  
							}
							if($actMenu == 2)
                            {
								echo "<div class=\"binWrapper\">
										<button class=\"highlightSvgOnHover\" formaction=\"/AdminController/editRequest\">
											<svg viewBox=\"-264 116.7 469.3 469.3\">
												<path class=\"st0\" d=\"M192.8,192.8l-64-64.1c-16.1-16.1-44.2-16.2-60.4,0l-286.7,289.5c-1.3,1.3-2.2,2.9-2.7,4.6l-42.7,149.5
												c-1.1,3.7,0,7.7,2.7,10.5c2,2,4.8,3.1,7.5,3.1c1,0,2-0.1,2.9-0.4l149.3-42.7c1.7-0.5,3.3-1.4,4.6-2.7l289.3-287
												c8.1-8.1,12.5-18.8,12.5-30.2S200.9,200.9,192.8,192.8z M22,206.4l39.3,39.3l-205,205l-14.7-29.4c-1.8-3.6-5.5-5.9-9.5-5.9h-17.1
												L22,206.4z M-237.8,559.8l13.9-48.6l34.7,34.7L-237.8,559.8z M-114.7,524.6l-51,14.6l-51.5-51.5l14.6-51h28l18.4,36.8
												c1,2.1,2.7,3.7,4.8,4.8l36.8,18.4L-114.7,524.6L-114.7,524.6z M-93.3,507.1V490c0-4-2.3-7.7-5.9-9.5l-29.4-14.7l205-205l39.3,39.3
												L-93.3,507.1z M177.8,238.4l-47,46.6L37,191.3l46.6-47c8.1-8.1,22.1-8.1,30.2,0l64,64c4,4,6.3,9.4,6.3,15.1
												S181.8,234.4,177.8,238.4z\"/>
											</svg> 
										</button>							
										<input type=\"hidden\" name=\"key\" value=\"".$row->email."\" />
									  	<input type=\"hidden\" name=\"actMenu\" value=\"".$actMenu."\" />	 
									  </div>
									  ";
                            }

                            echo "</form>";
					}
						echo "</div>";	
					?>
					</div>
           		</div>
		</div>
	</body>
	<script src="/js/passwordStrength/zxcvbn.js"></script>
    <script src="/js/passwordStrength/passwordStrength.js"></script>
	<script src="/js/admin/searchRender.js"></script>
</html>
