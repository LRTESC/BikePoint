import './bootstrap.js';
import './styles/app.css';
import 'bootstrap';
import '@ecommit/crud-bundle/js/crud';
import * as modalManager from '@ecommit/crud-bundle/js/modal/modal-manager';
import * as ajax from '@ecommit/crud-bundle/js/ajax';
import {trans, MESSAGE_DO_YOU_REALLY_WANT_TO_DELETE_THIS_BASE,} from './translator';

var modalEngine = require('@ecommit/crud-bundle/js/modal/engine/bootstrap5');
modalManager.defineEngine(modalEngine);

document.addEventListener('click', (event) => {
    if (event.target.closest('.confirm-delete')) {
        event.preventDefault();

        if (confirm(trans(MESSAGE_DO_YOU_REALLY_WANT_TO_DELETE_THIS_BASE))) {
             ajax.link(event.target.closest('.confirm-delete'), {
                'update': '#crud_list',
            });
        }
    }
});
