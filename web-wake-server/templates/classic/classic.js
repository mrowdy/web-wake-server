(function (fn){

    var instance = this,
        $submitButtons = document.querySelectorAll('.submit'),
        $form = document.querySelector('#wakeupForm'),
        $sleeper = document.querySelector('#sleeper');

    var init = function(){
        clickBindings();

    };

    var clickBindings = function(){
        for(var i = 0; i < $submitButtons.length; i++){
            addEvent($submitButtons[i], 'click', onSubmitClick);
        }
    }

    var addEvent = function(obj, type, fn) {
        if (obj.addEventListener)
            obj.addEventListener(type, fn, false);
        else if (obj.attachEvent)
            obj.attachEvent('on' + type, function() { return fn.apply(obj, [window.event]);});
    }

    var onSubmitClick = function(evt){
        var sleeperKey =  this.value;
        $sleeper.value = sleeperKey;

        var $sleeperEl = document.querySelector('#' + sleeperKey);
        console.log($sleeperEl);

        $form.submit();
    }

    init();

})('webwake');