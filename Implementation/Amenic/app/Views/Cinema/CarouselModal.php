<div class="modalWrapper" id="carouselWrapper" onclick="closeCarousel()">
    <div class="bigModal centerX" id="carouselModal">
        <!-- CAROUSEL HEADER -->
        <div class="modalHead spaceBetween">
            <div></div>
            <img src="/assets/close.svg" onclick="closeCarousel()" class="modalClose" alt="Close form" />
        </div>
        <!-- IMAGES -->
        <div class="bigModalBody centerRow" id="carouselImages">
            <?php
                if (isset($gallery) && count($gallery)>0)
                {
                    foreach ($gallery as $picture)
                    {
                        echo "
                            <img src=\"data:image/jpg;base64, ".$picture->image."\" class=\"galleryHidden\" id=\"".$picture->name."\" />
                        ";
                    }
                }
            ?>
        </div>
        <!-- CAROUSEL NAVIGATION -->
        <div class="row spaceBetween mt-2">
            <div class="movieSearchArrow movieSearchActiveControl column centerRow" id="leftCarouselArrow" onClick="carouselBack()">
                <img src="/assets/Movie/arrowLeft.svg" class="movieArrow" />
            </div>
            <?php
                if (isset($cinemaIsLoggedIn) && $cinemaIsLoggedIn)
                {
                    echo "
                        <form method=\"POST\" action=\"/Theatre/ActionDeleteImage\">
                            <input type=\"hidden\" name=\"deleteImageName\" value=\"\" id=\"imageNameForDelete\"/>
                            <button type=\"submit\" class=\"highlightSvgOnHover galleryDeleteItem\">
                                <svg viewBox=\"-286 137 346.8 427\">
                                <path d=\"M-53.6,291.7c-5.5,0-10,4.5-10,10v189c0,5.5,4.5,10,10,10s10-4.5,10-10v-189C-43.6,296.2-48.1,291.7-53.6,291.7 z\"/>
                                <path d=\"M-171.6,291.7c-5.5,0-10,4.5-10,10v189c0,5.5,4.5,10,10,10s10-4.5,10-10v-189 C-161.6,296.2-166.1,291.7-171.6,291.7z\"/>
                                <path d=\"M-257.6,264.1v246.4c0,14.6,5.3,28.2,14.7,38.1c9.3,9.8,22.2,15.4,35.7,15.4H-18c13.5,0,26.4-5.6,35.7-15.4 c9.3-9.8,14.7-23.5,14.7-38.1V264.1c18.5-4.9,30.6-22.8,28.1-41.9C58,203.2,41.8,189,22.6,189h-51.2v-12.5 c0.1-10.5-4.1-20.6-11.5-28c-7.4-7.4-17.6-11.6-28.1-11.5H-157c-10.5-0.1-20.6,4-28.1,11.5c-7.4,7.4-11.6,17.5-11.5,28V189h-51.2 c-19.2,0-35.4,14.2-37.9,33.3C-288.2,241.3-276.1,259.2-257.6,264.1z M-18,544h-189.2c-17.1,0-30.4-14.7-30.4-33.5V265h250v245.5 C12.4,529.3-0.9,544-18,544z M-176.6,176.5c-0.1-5.2,2-10.2,5.7-13.9c3.7-3.7,8.7-5.7,13.9-5.6h88.8c5.2-0.1,10.2,1.9,13.9,5.6 c3.7,3.7,5.7,8.7,5.7,13.9V189h-128V176.5z M-247.8,209H22.6c9.9,0,18,8.1,18,18s-8.1,18-18,18h-270.4c-9.9,0-18-8.1-18-18 S-257.7,209-247.8,209z\"/>
                                <path d=\"M-112.6,291.7c-5.5,0-10,4.5-10,10v189c0,5.5,4.5,10,10,10s10-4.5,10-10v-189 C-102.6,296.2-107.1,291.7-112.6,291.7z\"/>
                                </svg>
                            </button>
                        </form>
                    ";
                }
            ?>
            <div class="movieSearchArrow movieSearchActiveControl column centerRow" id="rightCarouselArrow" onClick="carouselForward()">
                <img src="/assets/Movie/arrowRight.svg" class="movieArrow" />
            </div>
        </div>
    </div>
</div>