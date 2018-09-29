/**
Mediotype Instant Rebate
@author R. Dale Owen
contains all display based js pertaining to the InstantRebate module
*/
//for creating popups
function customPrompt(id) {
// get field to be validated
    overlay(id);
    //headerOverlay();
    var pf = $(id);
    pf.addClassName('active');

    var popDim = pf.getDimensions();
    var browserDim = document.body.getDimensions();
    var x = (browserDim.width - popDim.width) / 2;
    var styles = {left: x + 'px'};

    pf.setStyle(styles);
    pf.scrollTo();

}

function closeClick(id) {
    //given a popup id, close it and overlays
    var p = $(id);
    if (p) {
        p.removeClassName('active');
        //removeHeaderOverlay();
        removeOverlay();
    }
}

function overlay(id) {
    var overlay = document.createElement("div");
    overlay.setAttribute("id", "overlay");
    overlay.setAttribute("class", "overlay");
    overlay.onclick = function () {
        closeClick(id)
    };
    document.body.appendChild(overlay);
}

function removeOverlay() {
    var o = document.getElementById("overlay");
    if(o){
        document.body.removeChild(o);
    }
}