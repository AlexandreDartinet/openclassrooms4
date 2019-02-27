$('#submitbtn').remove();
let defaultToolbar = 'fullscreen preview save | undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | indent outdent | ';
tinymce.init({
    selector: '#content',
    plugins: [
        'a11ychecker',
        'autolink',
        'autoresize',
        'autosave',
        'charmap',
        'fullscreen',
        'link',
        'image',
        'lists',
        'advlist',
        'media',
        'code',
        'pageembed',
        'paste',
        'permanentpen',
        'powerpaste',
        'preview',
        'print',
        'quickbars',
        'save',
        'searchreplace',
        'tinymcespellchecker',
        'table',
        'toc',
        'visualblocks',
        'wordcount'
    ],
    toolbar: defaultToolbar + 'bullist numlist | code visualblocks | forecolor backcolor | image link media | permanentpen | searchreplace',
    min_height: 200,
    image_caption: true,
    media_live_embeds: true,
    save_enablewhendirty: false,
    spellchecker_language: 'fr',
    image_list: "/generated/json/images.json"
});

// image_list: [
//     {title: 'My image 1', value: 'https://www.tinymce.com/my1.gif'},
//     {title: 'My image 2', value: 'http://www.moxiecode.com/my2.gif'}
//   ]
// image_list: "/ajax/images-list.json"
// image_prepend_url: "https://"+SITE_URL+"/generated/images/"