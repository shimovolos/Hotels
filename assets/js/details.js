$(function(){
    $('#slides').slides({
        preload: true,
        play: 5000,
        pause: 2500,
        hoverPause: true,
        animationStart: function(){
            $('.caption').animate({
                bottom:-35
            },100);
        },
        animationComplete: function(current){
            $('.caption').animate({
                bottom:0
            },200);
            if (window.console && console.log) {
                console.log(current);
            };
        }
    });
});

function ShowOrHide(id)
{
    var block = document.getElementById(id).style;

    if(block.display == 'none')
    {
        block.display = 'block';
    }
    else
    {
        block.display = 'none';
    }
}
