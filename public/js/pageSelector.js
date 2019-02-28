function updatePageSelector(element, last) {
    if(last > 1) {
        $('#page-selector').remove();
        let pageSelector = $(document.createElement('div')).attr('id', 'page-selector');
        if(curPage > 1) {
            pageSelector.append($(document.createElement('a'))
                .addClass('page-selector-item')
                .attr('id', 'page-prev-'+(curPage-1))
                .attr('href', path+'page-'+(curPage-1)+'/')
                .on('click', (e) => {
                    e.preventDefault();
                    loadPage(curPage-1);
                })
                .text('<'));
        }
        for(let page = 1; page <= last; page++) {
            let pageLink = $(document.createElement('a'))
                .addClass('page-selector-item')
                .attr('id', 'page-'+page)
                .text(page);
            if(page == curPage) {
                pageLink.addClass('page-current');
            }
            else {
                pageLink
                    .attr('href', path+'page-'+page+'/')
                    .on('click', (e) => {
                        e.preventDefault();
                        loadPage(page);
                    });
            }
            pageSelector.append(pageLink);
        }
        if(curPage != last) {
            pageSelector.append($(document.createElement('a'))
            .addClass('page-selector-item')
            .attr('id', 'page-next-'+(curPage+1))
            .attr('href', path+'page-'+(curPage+1)+'/')
            .on('click', (e) => {
                e.preventDefault();
                loadPage(curPage+1);
            })
            .text('>'));
        }
        element.append(pageSelector);
    }
};