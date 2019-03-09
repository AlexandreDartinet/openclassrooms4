$('div.post-bloc')
    .on('click', (e) => {
        tryRedirect($(e.target));
    })
    .css('cursor', 'pointer');

function tryRedirect(elt) {
    targetId = elt.attr('id');
    if((typeof targetId === "undefined")) {
        tryRedirect(elt.parent());
    }
    else if(targetId.match(/^post-\d+$/)) {
        id = targetId.split("-").pop();
        location.href = "/post/"+id+"/";
    }
    else {
        tryRedirect(elt.parent());
    }
}