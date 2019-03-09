function updatePageSelector(element, last) {
    if(last > 1) {
        $('#page-selector').remove();
        let pageSelector = $(document.createElement('nav'))
            .attr('id', 'page-selector')
            .addClass('pagination is-centered')
            .attr('role', 'navigation')
            .attr('aria-label', 'pagination')
        ;
        if(curPage > 1) {
            pageSelector.append($(document.createElement('a'))
                .addClass('page-selector-item pagination-previous')
                .attr('id', 'page-prev-'+(curPage-1))
                .attr('href', path+'page-'+(curPage-1)+'/')
                .on('click', (e) => {
                    e.preventDefault();
                    loadPage(curPage-1);
                })
                .text('<Précédente'));
        }
        let ul = $(document.createElement('ul')).addClass('pagination-list');
        for(let page = 1; page <= last; page++) {
            let pageLink = $(document.createElement('a'))
                .addClass('page-selector-item pagination-link')
                .attr('id', 'page-'+page)
                .text(page);
            if(page == curPage) {
                pageLink.addClass('page-current is-current');
            }
            else {
                pageLink
                    .attr('href', path+'page-'+page+'/')
                    .on('click', (e) => {
                        e.preventDefault();
                        loadPage(page);
                    });
            }
            ul.append($(document.createElement('li')).append(pageLink));
        }
        if(curPage != last) {
            pageSelector.append($(document.createElement('a'))
            .addClass('page-selector-item pagination-next')
            .attr('id', 'page-next-'+(curPage+1))
            .attr('href', path+'page-'+(curPage+1)+'/')
            .on('click', (e) => {
                e.preventDefault();
                loadPage(curPage+1);
            })
            .text('Suivante>'));
        }
        pageSelector.append(ul);
        element.append(pageSelector);
    }
};