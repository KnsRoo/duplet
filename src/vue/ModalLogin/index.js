import Vue from 'vue';
import Layout from './Layout.vue';

export default class LoginModal {
    
    constructor() {

        const el = document.getElementById('modal-login');
        const agreePageLink = el.dataset.agreePageLink;

        this.vmLoginModal = new Vue({
            
            name: 'LoginModal',

            data: {
                hidden: true,
            },

            el,

            methods: {
                toggle: function() {
                    this.hidden = !this.hidden;
                    document.body.classList.toggle('no-scroll');
                },
            },

            render: h => h(Layout,{
                props: {agreePageLink}    
            }),            
        });

        this.token = JSON.parse(localStorage.getItem('jwt'));
        this.loginBtn = document.querySelector('.js-login-btn');

        this.init();
    }

    init() {
        
        if ( this.loginBtn ) {
            this.loginBtn.addEventListener('click', () => {
                this.handleLoginClick();
            });
        }        
    }

    handleLoginClick() {
        if ( this.token && 'accessToken' in this.token ) {
            const a = document.createElement('a');
            a.href = '/lk';
            a.click();
        } else {
            this.vmLoginModal.toggle();
        }
    }

    toggle() {
        this.vmLoginModal.toggle();
    }
}
