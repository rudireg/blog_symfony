'use strict';

import $ from 'jquery';
import 'jqueryui';

if (!Element.prototype.remove) {
    Element.prototype.remove = function remove() {
        if (this.parentNode) {
            this.parentNode.removeChild(this);
        }
    };
}

/**
 * Управление списком новостей
 */
var Admin = {
    init: function () {
        let dels = document.querySelectorAll('button[act=delete]');
        if (dels) {
            dels.forEach(function (item, i, arr) {
                item.addEventListener('click', function () {
                    Admin.delete(this.getAttribute('news_id'));
                });
            });
        }
        let edits = document.querySelectorAll('button[act=edit]');
        if (edits) {
            edits.forEach(function (item, i, arr) {
                item.addEventListener('click', function () {
                    Admin.edit(this.getAttribute('news_id'));
                });
            });
        }
    },
    delete: function (id) {
        $.ajax({
            url: '/admin/news/delete',
            type: 'POST',
            dataType: 'json',
            data: {'id':id},
            success: function (data, textStatus, jqXHR) {
                if (data.success) {
                    let el = document.querySelector('#news_' + data.id);
                    el.remove();
                    el = document.querySelector('button[act=delete]');
                    if (!el) {
                        location.reload();
                    }
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('error delete');
            }
        });
    },
    edit: function (id) {
        console.log('edit ' + id);
    }
};

/**
 * Добавить или редактировать новость
 */
var AddNews = {
    form:null,
    id: null,
    title:null,
    text:null,
    theme:null,
    date:null,
    btn:null,
    init: function () {
        this.form = document.querySelector('#form-add-news');
        if (this.form === null) return;
        this.id = document.querySelector('#news-id');
        this.title = document.querySelector('#news-title');
        this.text = document.querySelector('#news-text');
        this.date = document.querySelector('#datepicker');
        this.theme = document.querySelector('#news-theme');
        this.btn = document.querySelector('#news-add');
        this.btn.addEventListener('click', function () {
            AddNews.add();
        })
    },
    reset: function () {
        this.id.value = '';
        this.title.value = '';
        this.text.value = '';
        this.theme.value = 1;
        this.date.value = '';
    },
    add: function () {
        let params = {
            'id': this.id.value,
            'title': this.title.value,
            'text': this.text.value,
            'theme': this.theme.value,
            'date': this.date.value,
        };
        $.ajax({
            url:'/admin/news/add',
            type:'POST',
            data: params,
            dataType:'json',
            success: function (data, textStatus, jqXHR) {
                if (data.success) {
                    alert('OK');
                    if (AddNews.id.value === '') {
                        AddNews.reset();
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('error add')
            }
        });
    }
};

/**
 * Ready
 */
window.onload = function () {
    Admin.init();
    AddNews.init();
    $( "#datepicker" ).datepicker();
};


