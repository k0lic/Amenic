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
                            <img src=\"data:image/jpg;base64, ".$picture->image."\" class=\"galleryHidden\" />
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
            <div class="movieSearchArrow movieSearchActiveControl column centerRow" id="rightCarouselArrow" onClick="carouselForward()">
                <img src="/assets/Movie/arrowRight.svg" class="movieArrow" />
            </div>
        </div>
    </div>
</div>