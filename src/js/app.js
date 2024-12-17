
/**
 * @author Endrődi Kálmán
 */

$(document).ready(function(){
    $('#btn-add').click(function(){
        $('#editor').show()
    })
    $("button[title='Módosít']").click(function() {
        $('#editor').show()
        $("html, body").animate({
            scrollTop: $('#editor').offset().top
        }, 500);
        
    })
    $('#btn-cancel').click(function(){
        $('#editor').hide()
    })
})

function btnEditOnClick(id, name, zip = 0) {
    $('#id').val(id);
    $('#name').val(name);
    if(zip != 0){
        $('#zip-code').val(zip);
        $('#counties-dropdown-modify').show();
    }
}

var last_id = 0;
function focus(recent_id){
    recent_id = parseInt(recent_id);
    if(recent_id != 0){
        var where = "#id-entity-" + recent_id;
        $(where).addClass("highlighted");
        $("html, body").animate({
            scrollTop: $(where).offset().top
        }, 500);

        if(last_id == 0) 
        {
            last_id = recent_id;
        }
        if(last_id != recent_id)
        {
            var where = "#id-entity-" + last_id;
            $(where).css("font-weight", "lighter");
        }
        last_id = recent_id;
    }
}

function search(){
    var needed = $('#needed').val();
    $.ajax({
        url: './index.php',
        type: 'POST',
        data: {needed: needed},
        success: function(data){
            if(data[934] == "\n"){
                focus(data[932]);
            }
            else {
                focus(data.substring(932,934));
            }
        } 
    });
}