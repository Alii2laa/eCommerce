<?php

    function getFolder(){
        return app() -> getLocale() === 'ar' ? 'css-rtl' : 'css';
    }
    define('PAGINATION_COUNT',15);

    function uploadImage($brandFolder,$photo){
        $photo->store('/',$brandFolder);
        $fileNmae = $photo->hashName();
        //$path = 'images/'. $brandFolder .'/'. $fileNmae;
        return $fileNmae;
    }

