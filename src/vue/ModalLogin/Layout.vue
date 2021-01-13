<template lang="pug">
transition(name="fade")
    .modal-login.js-modal-login(
        v-if="!$root.hidden"
    )
        .registration
            .registration__wrapper(v-if="mode == 'login'")
                a#close_btn_avtorisation.js-close(@click = "$root.toggle()")
                    svg.registration__close
                        use(href="/assets/icons/icons.svg#close")
                .registration__tilte Введите E-mail и пароль для входа
                .registration__inner
                    .registration__item
                        .registration__label.phone E-mail
                        input.registration__input.phone(v-model="email")
                    .registration__item
                        .registration__label.phone Пароль
                        input.registration__input.password(type = "password" v-model="password")
                        br
                        pre.registration__error(v-if="!isValidMail") Неверно введен email
                    .forget_password(@click="switchMode('restore')") Забыли пароль?
                    .go_to_registration(@click="switchMode('registration')") Зарегистрироваться
                button.registration__btn.button(:disabled ="!isValidMail" @click="login") Войти
            .login__wrapper(v-else-if = "mode == 'request'")
                a#close_btn_avtorisation.js-close(@click = "$root.toggle()")
                    svg.registration__close
                        use(href="/assets/icons/icons.svg#close")
                .registration__tilte Введите новый пароль
                .registration__inner
                    .registration__item
                        .registration__label.password Пароль
                        input.registration__input.password(type="password" v-model="password")
                    .registration__item
                        .registration__label.password Подтверждение пароля
                        input.registration__input.password(type="password" v-model = "verifiedPassword")
                        pre.registration__error(v-if="!equal") Пароли не совпадают
                        br
                        pre.registration__error(v-if="!isValidPass") Пароль должен содержать буквы и цифры
                    button.registration__btn.button(:disabled ="!isValidPass || !agree || !equal" @click="changePassword") Сохранить
            .login__wrapper(v-else-if = "mode == 'registration'")
                a#close_btn_avtorisation.js-close(@click = "$root.toggle()")
                    svg.registration__close
                        use(href="/assets/icons/icons.svg#close")
                .registration__tilte Регистрация
                .registration__inner
                    .registration__item
                        .registration__label.email E-mail
                        input.registration__input.email(v-model="email")
                    .registration__item
                        .registration__label.password Пароль
                        input.registration__input.password(type="password" v-model="password")
                    .registration__item
                        .registration__label.password Подтверждение пароля
                        input.registration__input.password(type="password" v-model = "verifiedPassword")
                        pre.registration__error(v-if="!equal") Пароли не совпадают
                        br
                        pre.registration__error(v-if="!isValidPass") Пароль должен содержать буквы и цифры
                    .registration__agreement
                        input(type="checkbox" v-model="agree")
                        .registration__agreement-text Cогласие на обработку персональных данных 
                            br
                            span Политика конфиденциональности
                    a.go_to_registration(@click= "switchMode('login')") К авторизации
                    button.registration__btn.button(:disabled ="!isValidMail || !isValidPass || !agree || !equal" @click="register") Зарегистрироваться
            .login__wrapper(v-else-if = "mode == 'info'")
                a#close_btn_avtorisation.js-close(@click = "$root.toggle()")
                    svg.registration__close
                        use(href="/assets/icons/icons.svg#close")
                .login__tilte Подтвердите E-mail
                .registration__inner
                    .registration__label.email На Вашу почту выслан код с подтверждением
            .login__wrapper(v-else-if = "mode == 'restore'")
                a#close_btn_avtorisation.js-close(@click = "$root.toggle()")
                    svg.registration__close
                        use(href="/assets/icons/icons.svg#close")
                .login__tilte Введите E-mail
                .registration__inner
                    .registration__item
                        .registration__label.email E-mail
                        input.registration__input.email(v-model = "email")
                        pre.registration__error(v-if="!isValidMail") Неверно введен email
                    a.go_to_registration(@click= "switchMode('login')") К авторизации
                    button.registration__btn.button(:class = "{disabled: !isValidMail}" @click="requestRestore") Восстановление


</template>
<script>

import authfetch from '../../js/components/authfetch';
import noty from '../../js/components/noty';


export default {

    components: {
    },

    props: {

    },
    
    data() {
        return { 
            id: null,
            restoreToken: null,
            email: "",
            password: "",
            verifiedPassword: "",
            mode: "login",
            agree: false,
            isError: false,
            modes: {
                'login': 'Вход',
                'registration': 'Регистрация',
                'loading': 'Загрузка',
                'info': 'Подтверждение email',
                'success': 'Подтверждение email',
                'request': 'Подтверждение email',
                'restore': 'Восстановление пароля',
            }
        }

    },

    computed: {
        isValidMail() {
            const re = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/g
            return this.email.match(re)
        },
        isValidPass(){
            const re = /(?=.*[0-9])[0-9a-zA-Z!@#$%^&*]{6,}/g
            // const re = /(?=.*[0-9])(?=.*[!@#$%^&;*])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&*]{6,}/g
             return this.password.match(re)
            //return this.password.length > 0
        },
        equal(){
            return (this.password === this.verifiedPassword && this.password.length != 0);
        },
        caption(){
            return this.modes[this.mode]
        }
    },

    methods: {

        switchMode(mode){
            this.mode = mode
        },

        closeSuccessfully() {
            this.$emit('toggle');
            this.email = '';
            this.password = '';
            this.verifiedPassword = '';
            this.switchMode('login');
        },

        async register(){
            try {
                let response = await fetch('api/auth/basic/register', {
                    method: 'POST',
                    body: JSON.stringify({
                        'email': this.email,
                        'password': this.password
                    })
                })
                if ( response.ok ) {
                    this.switchMode("info");
                } else {
                    result = await response.json();
                    throw result.errors[0].message;
                }
               
            } catch (err){
                const message = typeof(err) == 'string' ?  err : 'Неизвестная ошибка'
                noty('error', message);
            }
        },

        async login(){  
            let result = null
            try {
                let response = await fetch('api/auth/basic/login', {
                    method: 'POST',
                    body: JSON.stringify({
                        'email': this.email,
                        'password': this.password
                    })
                }) 
                if ( response.ok ) {
                    result = await response.json();
                    localStorage.setItem('jwt', JSON.stringify(result));
                    this.closeSuccessfully();
                    document.location.href = '/lk'
                } else {
                    result = await response.json();
                    throw result.errors[0].message
                }

            } catch(err) {
                const message = typeof(err) == 'string' ?  err : 'Ошибка авторизации'
                noty('error', message);
            }
        },

        async changePassword(){
            try {
                let response = await fetch('/api/auth/basic/restore', {
                    method: 'POST',
                    body: JSON.stringify({
                        'token': this.restoreToken,
                        'id': this.id,
                        'password': this.password
                    })
                })
                if (response.ok){
                    this.switchMode("login")
                    noty('success', 'Пароль успешно изменен');  
                } else {
                    result = await response.json();
                    throw result.errors[0].message;
                }
            } catch (err){
                const message = typeof(err) == 'string' ?  err : 'Неизвестная ошибка'
                noty('error', message);     
            }
        },

        async requestRestore(){
            try{
                let response = await fetch('/api/auth/basic/restore/request', {
                    method: 'POST',
                    body: JSON.stringify({
                        'email': this.email,
                    })
                })
                if (response.ok){
                    this.switchMode("info")  
                } else {
                    result = await response.json();
                    throw result.errors[0].message;
                }
            } catch (err){
                const message = typeof(err) == 'string' ?  err : 'Неизвестная ошибка'
                noty('error', message);       
            }    
        }

    },

    watch: {

    },

    mounted() {

    },

    async created() {
        let urlParams = new URL(window.location.href).searchParams
        let mode = urlParams.get('mode')
        let token = urlParams.get('token')
        let id = urlParams.get('id')
        let result = null
        if (!(token && id && mode)) return
        else {
            if (localStorage.getItem('jwt')){
                noty('error', 'Ошибка');  
            return
            }
        }
        this.mode = 'loading'
        if (mode == "confirm"){
            try{
                let response = await fetch('/api/auth/basic/confirm', {
                    method: 'POST',
                    body: JSON.stringify({
                        'token': token,
                        'id': id
                    })
                })
                if (response.ok){
                    this.switchMode("login")
                    noty('success', 'Учетная запись успешно активирована');  
                } else {
                    result = await response.json();
                    throw result.errors[0].message;
                }
            } catch (err){
                const message = typeof(err) == 'string' ?  err : 'Неизвестная ошибка'
                noty('error', message);       
            }
        } else if (mode == "restore"){
            try{
                let response = await fetch('/api/auth/basic/valid', {
                    method: 'POST',
                    body: JSON.stringify({
                        'token': token,
                        'id': id
                    })
                })
                if (response.ok){
                    this.switchMode("restore")
                    this.restoreToken = token
                    this.id = id
                } else {
                    result = await response.json();
                    throw result.errors[0].message;
                }
            } catch (err) {
                const message = typeof(err) == 'string' ?  err : 'Неизвестная ошибка'
                noty('error', message);
            }
        }

    },    

}
</script>


<style scoped lang="scss">
.passwordinvalid{
    color: red;
    font-size: 12px;
}
.fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active до версии 2.1.8 */ {
  opacity: 0;
}
</style>
