import Vue from 'vue'
import $ from 'jquery'

var currentModal = undefined;
var currentModalCallback = undefined;
    
export default {

    show ( component, data, callback, store ) {
        const ModalComponent = Vue.extend( component );
        const modalInstance = new ModalComponent({ propsData: data, store });
        modalInstance.$mount();
        currentModal = $(modalInstance.$el);
        currentModal.modal({
            closable: false,
            transition: 'fade',
            duration: 200,
            autofocus: false,
            onHidden: function() {
                currentModal.remove();
                currentModal = undefined;
                currentModalCallback = undefined;
            },
            onApprove: function() {
                return false;
            }
        });
        currentModal.modal('show');
        currentModalCallback = callback;
    },

    success ( data ) {
        if(currentModalCallback) currentModalCallback(data);
        currentModal.modal('hide');
    },

    hide () {
        if(currentModal !== undefined) {
            currentModal.modal('hide');
        }
    },

    refresh () {
        if(currentModal !== undefined) {
            currentModal.modal('refresh');
        }
    },

    isOpen () {
        return currentModal !== undefined;
    },

}
