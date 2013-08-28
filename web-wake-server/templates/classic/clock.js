Clock = function($el){

    var instance = this,
        $display = document.querySelector('.display', $el),
        $left = document.querySelector('.rotate.left', $el),
        $right = document.querySelector('.rotate.right', $el),
        $front = document.querySelector('.front', $el),

        time = 0,
        angle = 0;


    this.update = function(){
        console.log('update clock');
    }

    var init = function(){
        console.log('init clock');
    }

    var getAngle = function(){
        console.log('getAngle');
    }

    var getTime = function(){
        console.log('getTime');
    }


    init();
}