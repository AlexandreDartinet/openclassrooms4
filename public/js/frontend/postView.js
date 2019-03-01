let data;
let updateTimer;
let reloadTimer;
let timeoutTimer;
let timeout = true;
let action = false;

/**
 * Remet à zéro le timeout qui arrête la mise à jour automatique des commentaires.
 */
function resetTimeout() {
    if(timeout) {
        $.getJSON(siteUrl+"ajax/comments/get/"+postId+"/",(response) => initComments(response));
        reloadTimer = setInterval(() => {$.getJSON(siteUrl+"ajax/comments/get/"+postId+"/", (response) => {initComments(response)})}, 60000);
    }
    timeout = false;
    clearTimeout(timeoutTimer);
    timeoutTimer = setTimeout(() => {
        clearInterval(reloadTimer);
        clearInterval(updateTimer);
        timeout = true;
    }, 600000);
}

/**
 * Initialise la page des commentaires avec les commentaires récupérés en ajax.
 * 
 * @param {Object} response 
 */
function initComments(response) {
    data = response;
    loadPage(curPage, true);
    clearInterval(updateTimer);
    updateTimer = setInterval(() => forceUpdate(), 10000);
};

/**
 * Force une mise à jour des commentaires
 */
function forceUpdate() {
    $.getJSON(siteUrl+"ajax/comments/update/"+postId+"/"+data.lastId+"/", (response) => updateComments(response))
};

/**
 * Met à jour data avec les derniers commentaires et met à jour les données affichées.
 * @param {Object} response 
 */
function updateComments(response) {
    let updateData = response;
    if(updateData.lastId != data.lastId) {
        highlight = [];
        updateData.comments.forEach((comment) => {
            highlight.push(comment.id);
            if(comment.isReply) {
                let parentIndex = data.comments.findIndex((e) => {
                    return e.id == comment.replyTo;
                });
                data.comments[parentIndex].replies.push(comment);
                data.comments[parentIndex].repliesNbr += 1;
            }
            else {
                data.comments.push(comment);
                data.commentsNbr += 1;
            }
        });
        data.lastId = updateData.lastId;
        loadPage(curPage, true);
        highlight.forEach((id) => {
            $('#comment-'+id).addClass('comment-highlight');
            setTimeout(() => {
                $('#comment-'+id).removeClass('comment-highlight');
            },2000);
        })
    } 
}

/**
 * Met à jour les données affichées pour la page (page), (auto) nous dit si le changement de page a été déclenché par l'utilisateur ou non.
 * 
 * @param {int} page 
 * @param {boolean} auto 
 */
function loadPage(page, auto = false) {
    if(!action) {
        if(!auto) {
            resetTimeout();
        }
        let comments = $('#comments-div');
        curPage = page;
        comments.html('');
        for(let i=data.commentPage*(page-1); (i <=data.commentPage*page) && (i < data.comments.length); i++) {
            let comment = data.comments[i];
            comments.append(displayComment(comment));
            comment.replies.forEach((reply) => {
                comments.append(displayComment(reply));
            });
        }
        showButtons();
        updateButtons();
        updatePageSelector($('#comments'), Math.ceil(data.commentsNbr/data.commentPage));
    }
}

/**
 * Intercepte les actions pour chaque bouton des commentaires.
 */
function updateButtons() {
    $('.comment-reply-link').on('click', (e) => {
        e.preventDefault();
        targetId = $(e.target).attr('id');
        id = parseInt(targetId.split("-").pop());
        showReplyComment(id);
    });
    $('.comment-edit-link').on('click', (e) => {
        e.preventDefault();
        targetId = $(e.target).attr('id');
        id = parseInt(targetId.split("-").pop());
        showEditComment(id);
    });
    $('.comment-delete-link').on('click', (e) => {
        e.preventDefault();
        targetId = $(e.target).attr('id');
        id = parseInt(targetId.split("-").pop());
        deleteComment(id);
    });
    $('.comment-report-link').on('click', (e) => {
        e.preventDefault();
        targetId = $(e.target).attr('id');
        id = parseInt(targetId.split("-").pop());
        showReportComment(id);
    });
};

/**
 * Affiche le formulaire de réponse à un commentaire
 * 
 * @param {int} id 
 */
function showReplyComment(id) {
    action = true;
    hideButtons();
    resetTimeout();
    comment = findComment(id);
    if(comment.repliesNbr == 0) {
        appendAfter = $('#comment-'+comment.id);
    }
    else {
        appendAfter = $('.comment-reply-to-'+comment.id).last();
    }
    form = $(document.createElement('form'))
        .append($(document.createElement('div'))
            .append($(document.createElement('input'))
                .attr('type', 'text')
                .attr('name', 'name')
                .attr('required', true)
                .attr('placeholder', 'Votre nom')
                .attr('value', data.user.name)
                .attr('readonly', (data.user.id != 0))
            )
        )
        .append($(document.createElement('div'))
            .append($(document.createElement('textarea'))
                .attr('name', 'content')
                .attr('required', true)
                .attr('placeholder', 'Votre commentaire')
                .attr('id', 'to-focus')
            )
        )
        .append($(document.createElement('input'))
            .attr('type', 'submit')
        )
        .append($(document.createElement('input'))
            .attr('type', 'reset')
            .attr('value', 'Annuler')
            .on('click', (e) => {
                action = false;
                form.remove();
                showButtons();
            })
        );
    form.submit((e) => {
        e.preventDefault();
        action = false;
        let formData = $(e.target).serializeArray();
        let postData = {};
        formData.forEach((d) => {
            postData[d.name] = d.value;
        });
        postData["id_post"] = ""+postId;
        postData["reply_to"] = ""+comment.id;
        sendComment(postData);
        form.remove();
        showButtons();
        resetTimeout();
    });
    if(comment.repliesNbr > 0) form.addClass('comment-reply');
    appendAfter.after(form);
    $('#to-focus').focus();
};

/**
 * Affiche le formulaire d'édition d'un commentaire
 * @param {int} id 
 */
function showEditComment(id) {
    action = true;
    resetTimeout();
    hideButtons();
    comment = findComment(id);
    commentElt = $('#comment-'+id);
    formElt = $(document.createElement('div'));
    oldCommentElt = commentElt.replaceWith(formElt);
    form = $(document.createElement('form'))
        .append($(document.createElement('div'))
            .append($(document.createElement('input'))
                .attr('type', 'text')
                .attr('name', 'name')
                .attr('required', true)
                .attr('placeholder', 'Votre nom')
                .attr('value', comment.author.name)
                .attr('readonly', (data.user.id != 0))
            )
            .append(" le "+comment.date)     
        )
        .append($(document.createElement('div'))
            .append($(document.createElement('textarea'))
                .attr('name', 'content')
                .attr('required', true)
                .attr('placeholder', 'Votre commentaire')
                .attr('id', 'to-focus')
                .val(comment.content)
            )
        )
        .append($(document.createElement('div'))
            .append($(document.createElement('input'))
                .attr('type', 'submit')
            )
            .append($(document.createElement('input'))
                .attr('type', 'reset')
                .attr('value', 'Annuler')
                .on('click', (e) => {
                    e.preventDefault();
                    action = false;
                    form.remove();
                    loadPage(curPage, true);
                    showButtons();
                    updateButtons();
                })
            )
        )
    ;
    if(comment.isReply) form.addClass('comment-reply');
    formElt.append(form);
    $('#to-focus').focus();
    form.submit((e) => {
        e.preventDefault();
        let formData = form.serializeArray();
        let postData = {};
        formData.forEach((d) => {
            postData[d.name] = d.value;
        });
        postData["id"] = comment.id;
        postData["reply_to"] = comment.replyTo;
        if(postData.content == comment.content && postData.name == comment.author.name) {
            showError("Vous n'avez rien modifié.");
        }
        else {
            modifyComment(postData);
        }
        action = false;
        resetTimeout();
        showButtons();
        updateButtons();
        form.remove();
        formElt.replaceWith(oldCommentElt);
    });
};

/**
 * Affiche le formulaire de signalement d'un commentaire
 * @param {int} id 
 */
function showReportComment(id) {
    console.log("Comment report : "+id);
    action = true;
    hideButtons();
    resetTimeout();
    comment = findComment(id);
    appendAfter = $('#comment-'+id);
    formElt = $(document.createElement('div'));
    form = $(document.createElement('form'))
        .append($(document.createElement('div'))
            .append($(document.createElement('select'))
                .attr('required', true)
                .attr('name', 'type')
                .attr('id', 'type')
            )
        )
        .append($(document.createElement('div'))
            .append($(document.createElement('textarea'))
                .attr('name', 'content')
                .attr('placeholder', 'Aucun commentaire')
                .attr('id', 'to-focus')
            )
        )
        .append($(document.createElement('input'))
            .attr('type', 'submit')
        )
        .append($(document.createElement('input'))
            .attr('type', 'reset')
            .attr('value', 'Annuler')
            .on('click', (e) => {
                action = false;
                formElt.remove();
                showButtons();
            })
        )
    ;
    if(comment.isReply) form.addClass('comment-reply');
    formElt.append(form);
    appendAfter.append(formElt);
    for(let i=0; i<data.reportTypes.length; i++) {
        $('#type').append($(document.createElement('option'))
            .attr('value', i)
            .text(data.reportTypes[i])
        );
    }
    $('#to-focus').focus();
    form.submit((e) => {
        e.preventDefault();
        action = false;
        let formData = $(e.target).serializeArray();
        let postData = {};
        formData.forEach((d) => {
            postData[d.name] = d.value;
        });
        postData['id_comment'] = ""+id;
        sendReport(postData);
        formElt.remove();
        showButtons();
        resetTimeout();
    });
};

/**
 * Envoie un commentaire en ajax et force une mise à jour si l'envoi est un succes
 * @param {Object} postData 
 */
function sendComment(postData) {
    $.ajax({
        type: "POST",
        url: siteUrl+'ajax/comments/send/',
        data: postData,
        success: (response) => {
            if(typeof response.success !== 'undefined') {
                showSuccess(response.success);
                if(postData['reply_to'] == 0) {
                    curPage = Math.ceil((data.commentsNbr+1)/data.commentPage)
                }
                forceUpdate();
            }
            else {
                showError(response.error);
            }
        },
        dataType: "json",
        error: (response) => {
            console.error(response);
        }
    });
};

/**
 * Modifie le contenu d'un commentaire et raffraichit les données si la modification est un succès.
 * @param {Object} postData 
 */
function modifyComment(postData) {
    $.ajax({
        type: "POST",
        url: siteUrl+'ajax/comments/modify/',
        data: postData,
        success: (response) => {
            if(typeof response.success !== 'undefined') {
                showSuccess(response.success);
                $.getJSON(siteUrl+"ajax/comments/get/"+postId+"/",(response) => initComments(response));
            }
            else {
                showError(response.error);
            }
        },
        dataType: "json",
        error: (response) => {
            console.error(response);
        }
    });
};

/**
 * Supprime un commentaire
 * @param {int} id 
 */
function deleteComment(id) {
    postData = {"id": id};
    $.ajax({
        type: "POST",
        url: siteUrl+'ajax/comments/delete/',
        data: postData,
        success: (response) => {
            if(typeof response.success !== 'undefined') {
                showSuccess(response.success);
                comment = findComment(id);
                if(comment.isReply) {
                    parentIndex = data.comments.findIndex((e) => {
                        return e.id == comment.replyTo;
                    });
                    replyIndex = data.comments[parentIndex].replies.findIndex((e) => {
                        return e.id == comment.id;
                    });
                    data.comments[parentIndex].replies.splice(replyIndex,1);
                    data.comments[parentIndex].repliesNbr -= 1;
                }
                else {
                    commentIndex = data.comments.findIndex((e) => {
                        return e.id == comment.id;
                    });
                    if(comment.repliesNbr == 0) {
                        data.comments.splice(commentIndex, 1);
                        data.commentsNbr -= 1;
                    }
                    else {
                        data.comments[commentIndex].content = "<Supprimé>";
                        data.comments[commentIndex].author.id = 0;
                        data.comments[commentIndex].author.name = "Supprimé";
                        data.comments[commentIndex].author.nameDisplay = "Supprimé";
                        data.comments[commentIndex].canEdit = false;
                    }
                }
                loadPage(curPage, true);
            }
            else {
                showError(response.error);
            }
        },
        dataType: "json",
        error: (response) => {
            console.error(response);
        }
    })
};

/**
 * Envoie un signalement et affiche une confirmation en cas de succès.
 * @param {Object} postData 
 */
function sendReport(postData) {
    console.log(postData);
    $.ajax({
        type: "POST",
        url: siteUrl+'ajax/reports/send/',
        data: postData,
        success: (response) => {
            if(typeof response.success !== 'undefined') {
                showSuccess(response.success);
            }
            else {
                showError(response.error);
            }
        },
        dataType: "json",
        error: (response) => {
            console.error(response);
        }
    });
};

/**
 * Affiche les boutons d'interaction
 */
function showButtons() {
    $('.comment-reply-link').show();
    $('.comment-edit-link').show();
    $('.comment-delete-link').show();
    $('.comment-report-link').show();
    $('#page-selector').show();
};

/**
 * Masque les boutons d'interaction
 */
function hideButtons() {
    $('.comment-reply-link').hide();
    $('.comment-edit-link').hide();
    $('.comment-delete-link').hide();
    $('.comment-report-link').hide();
    $('#page-selector').hide();
};

/**
 * Formate un commentaire passé en argument pour être affiché.
 * @param {Object} commentData 
 */
function displayComment(commentData) {
    let comment = $(document.createElement('div'))
        .addClass('comment')
        .attr('id', 'comment-'+commentData.id);
    if(commentData.isReply) {
        comment.addClass('comment-reply comment-reply-to-'+commentData.replyTo);
    }
    let info = $(document.createElement('p'))
        .append($(document.createElement('strong')).html(commentData.author.nameDisplay))
        .append(" le "+commentData.date+" ");
    if(typeof commentData.ip !== 'undefined') info.append("IP("+commentData.ip+") ");
    if(data.user.canComment)  {
        buttons = $(document.createElement('div')).addClass('comment-buttons');
        if(!commentData.isReply) {
            buttons.append($(document.createElement('a'))
                .attr('title', 'Répondre')
                .addClass('fas fa-reply comment-reply-link')
                .attr('id', 'comment-reply-link-'+commentData.id)
                .attr('href', '/post/'+postId+'/page-'+curPage+'/reply_to/'+commentData.id+'/'));
        }
        if(commentData.canEdit) {
            buttons
                .append($(document.createElement('a'))
                    .attr('title', 'Éditer')
                    .addClass('fas fa-edit comment-edit-link')
                    .attr('id', 'comment-edit-link-'+commentData.id)
                    .attr('href', '/post/'+postId+'/page-'+curPage+'/edit/'+commentData.id+'/'))
                .append($(document.createElement('a'))
                    .attr('title', 'Supprimer')
                    .addClass('fas fa-trash comment-delete-link')
                    .attr('id', 'comment-delete-link-'+commentData.id)
                    .attr('href', '/post/'+postId+'/page-'+curPage+'/delete/'+commentData.id+'/'));
        }
        buttons.append($(document.createElement('a'))
            .attr('title', 'Signaler')
            .addClass('fas fa-flag comment-report-link')
            .attr('id', 'comment-report-link-'+commentData.id)
            .attr('href', '/post/'+postId+'/report/'+commentData.id+'/'));
        if(typeof commentData.reportsNbr !== 'undefined') {
            buttons.append($(document.createElement('a'))
                .attr('title', 'Signalements('+commentData.reportsNbr+')')
                .addClass('fas fa-exclamation-triangle comment-reports-link')
                .attr('id', 'comment-reports-link-'+commentData.id)
                .attr('href', '/admin/reports/comment/'+commentData.id+'/')
                .text('('+commentData.reportsNbr+')'));
        }
        info.append(buttons);
    }
    comment.append(info);
    let content = $(document.createElement('p')).append(nl2br(htmlspecialchars(commentData.content)));
    comment.append(content);
    return comment;
};

/**
 * Trouve un commentaire par son identifiant dans data
 * @param {int} id 
 */
function findComment(id) {
    found = data.comments.find((comment) => {
        return comment.id == id;
    });
    if(typeof found === 'undefined') {
        found = data.comments.find((comment) => {
            return comment.replies.find((reply) => {
                return reply.id == id;
            }).id == id;
        }).replies.find((comment) => {
            return comment.id == id;
        });
    }
    return found;
}

/**
 * On initialise le script pour la page en lançant l'update automatique.
 */
resetTimeout();

/**
 * On intercepte le formulaire par défaut.
 */
$('#comment-form-div form').submit((e) => {
    e.preventDefault();
    let formData = $(e.target).serializeArray();
    let postData = {};
    formData.forEach((d) => {
        postData[d.name] = d.value;
    });
    postData["id_post"] = ""+postId;
    postData["reply_to"] = "0";
    sendComment(postData);
    resetTimeout();
    $('#comment-form-div form textarea').val('');
})
