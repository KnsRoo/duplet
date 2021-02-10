<template lang="pug">
transition(name="fade")
    .modal-login.js-modal-login(
        v-if="!$root.hidden"
    )
        // -.debug
            a.switch(@click="switchMode('login')") Вход
            a.switch(@click="switchMode('registration')") Регистрация
            a.switch(@click="switchMode('info')") Информация
            a.switch(@click="switchMode('request')") Восстановление
            a.switch(@click="switchMode('restore')") Подтверждение
        .registration
            .registration__wrapper(v-if="mode == 'login'")
                a#close_btn_avtorisation.js-close.icon-menu-cancel(@click = "close")
                .registration__title Вход в личный кабинет
                .registration__tip
                    span.noaccount Ещё нет аккаунта?
                    span.noaccount__tip(@click="switchMode('registration')") Зарегистрироваться
                .registration__inner
                    .registration__item
                        input.registration__input.phone(v-model="email" placeholder="e-mail")
                    .registration__error {{ (isValidMail) ? '' : 'Некорректно введен email' }} 
                    .registration__item
                        input.registration__input.password(type = "password" v-model="password" placeholder = "Пароль")
                    .registration__error {{ (password.length > 0 && password.length < 6) ? 'Пароль слишком короткий' : '' }} 
                .registration__tip
                    span.restore Забыли пароль?
                    span.restore__tip(@click="switchMode('request')") Восстановление
                .registration__button_wrapper
                    button.registration__btn.button(:disabled ="!isValidMail || password.length < 6" @click="login") ВОЙТИ
            .registration__wrapper(v-else-if = "mode == 'restore'")
                a#close_btn_avtorisation.js-close(@click = "close")
                    img.registration__close(src="/assets/img/icons/close.svg")
                .registration__title.long Введите новый пароль
                .registration__inner
                    .registration__item
                        input.registration__input.password(@input = "checkValid" type="password" v-model="password" placeholder = "Пароль")
                    .registration__error {{ passErrorText }} 
                    .registration__item
                        input.registration__input.password(type="password" v-model = "verifiedPassword" placeholder = "Подтверждение пароля")
                    .registration__error {{ (equal || (password.length == 0 || verifiedPassword.length == 0)) ? '' : 'Пароли не совпадают' }} 
                .registration__button_wrapper
                        button.registration__btn.button.save(:disabled ="!isValidPass || !agree || !equal" @click="changePassword") СОХРАНИТЬ
            .registration__wrapper(v-else-if = "mode == 'registration'")
                a#close_btn_avtorisation.js-close(@click = "close")
                    img.registration__close(src="/assets/img/icons/close.svg")
                .registration__title.long Регистрация
                .registration__inner
                    .registration__item
                        input.registration__input.email(v-model="email" placeholder = "e-mail")
                    .registration__error {{ (isValidMail) ? '' : 'Неверно введен email' }} 
                    .registration__item
                        input.registration__input.password(@input = "checkValid" type="password" v-model="password" placeholder = "Пароль")
                    .registration__error {{ passErrorText }} 
                    .registration__item
                        input.registration__input.password(type="password" v-model = "verifiedPassword" placeholder = "Подтверждение пароля")
                    .registration__error {{ (equal || (password.length == 0 || verifiedPassword.length == 0)) ? '' : 'Пароли не совпадают' }} 
                    .registration__agreement
                        input(type="checkbox" v-model="agree")
                        .registration__agreement-text Cогласие на обработку персональных данных 
                            span Политика конфиденциональности
                    .registration__tip
                        span.noaccount__tip(@click="switchMode('login')") К авторизации
                    .registration__button_wrapper
                        button.registration__btn.button(:disabled ="!isValidMail || !isValidPass || !agree || !equal" @click="register") ЗАРЕГИСТРИРОВАТЬСЯ
            .registration__wrapper(v-else-if = "mode == 'info'")
                a#close_btn_avtorisation.js-close(@click = "close")
                    img.registration__close(src="/assets/img/icons/close.svg")
                .registration__title.long Подтвердите E-mail
                .registration__inner
                    .registration__label На Вашу почту выслан код с подтверждением
            .registration__wrapper(v-else-if = "mode == 'success'")
                a#close_btn_avtorisation.js-close(@click = "close")
                    svg.registration__close
                        use(href="/assets/icons/icons.svg#close")
                .registration__title.long Подтверждение E-mail
                .registration__inner
                    .registration__label Учетная запись успешно активирована
                .registration__button_wrapper
                    button.registration__btn.button(@click= "switchMode('login')") ВОЙТИ
            .registration__wrapper(v-else-if = "mode == 'request'")
                a#close_btn_avtorisation.js-close(@click = "close")
                    img.registration__close(src="/assets/img/icons/close.svg")
                .registration__title.long Введите E-mail
                .registration__inner
                    .registration__item
                        input.registration__input.email(v-model = "email" placeholder = "e-mail")
                .registration__error {{ (isValidMail) ? '' : 'Неверно введен email' }} 
                .registration__tip
                    span.noaccount__tip(@click="switchMode('login')") К авторизации
                .registration__button_wrapper
                    button.registration__btn.button(:disabled = "!isValidMail || email.length == 0" @click="requestRestore") ВОССТАНОВЛЕНИЕ


</template>
<script>
import authfetch from "../../js/components/authfetch";
import noty from "../../js/components/noty";

export default {
    components: {},

    props: {},

    data() {
        return {
            id: null,
            restoreToken: null,
            email: "",
            password: "",
            verifiedPassword: "",
            passErrorText: "",
            mode: "login",
            agree: false,
            isError: false,
            modes: {
                login: "Вход",
                registration: "Регистрация",
                loading: "Загрузка",
                info: "Подтверждение email",
                success: "Подтверждение email",
                request: "Подтверждение email",
                restore: "Восстановление пароля"
            }
        };
    },

    computed: {
        isValidMail() {
            const re = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/g;
            if (this.email.length == 0) return true;
            return this.email.match(re);
        },
        isValidPass() {
            const re = /^(?=.*\d)(?=.*[a-zA-Z])(?!.*\s)(?=^.{6,}$).*$/;
            return this.password.match(re);
            // const re = /(?=.*[0-9])(?=.*[!@#$%^&;*])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&*]{6,}/g
            //return this.password.length > 0
        },
        equal() {
            return (
                this.password === this.verifiedPassword &&
                this.password.length != 0 &&
                this.verifiedPassword.length != 0
            );
        },
        caption() {
            return this.modes[this.mode];
        }
    },

    methods: {
        switchMode(mode) {
            this.mode = mode;
            this.password = "";
            this.verifiedPassword = "";
            this.email = "";
        },

        checkValid() {
            const re = /^(?=.*\d)(?=.*[a-zA-Z])(?!.*\s)(?=^.{6,}$).*$/;
            if (this.password.length == 0) {
                this.passErrorText = "";
            } else {
                if (this.password.length > 0 && this.password.length < 6) {
                    this.passErrorText = "Пароль слишком короткий";
                } else {
                    if (!this.password.match(re)) {
                        this.passErrorText =
                            "Пароль должен содержать буквы и цифры";
                    } else {
                        this.passErrorText = "";
                    }
                }
            }
        },

        close() {
            if (this.mode === "login"){
                window.location.href = "/";
            }
            else if (
                this.mode == "info" ||
                this.mode == "success" ||
                this.mode == "request"
            ) {
                this.switchMode("login");
            } else if ((this.mode = "restore")) {
                window.location.href = "/lk";
            }
            this.$root.toggle();
        },

        closeSuccessfully() {
            this.$emit("toggle");
            this.email = "";
            this.password = "";
            this.verifiedPassword = "";
            this.switchMode("login");
        },

        async register() {
            try {
                let response = await fetch("api/auth/basic/register", {
                    method: "POST",
                    body: JSON.stringify({
                        email: this.email,
                        password: this.password
                    })
                });
                if (response.ok) {
                    this.switchMode("info");
                } else {
                    result = await response.json();
                    throw result.errors[0].message;
                }
            } catch (err) {
                const message =
                    typeof err == "string" ? err : "Неизвестная ошибка";
                noty("error", message);
            }
        },

        async login() {
            let result = null;
            try {
                let response = await fetch("api/auth/basic/login", {
                    method: "POST",
                    body: JSON.stringify({
                        email: this.email,
                        password: this.password
                    })
                });
                if (response.ok) {
                    result = await response.json();
                    localStorage.setItem("jwt", JSON.stringify(result));
                    this.closeSuccessfully();
                    if  (window.location.pathname == '/favorite'){
                        document.location.href = "/favorite";
                    } else {
                        document.location.href = "/lk";
                    }
                    
                } else {
                    result = await response.json();
                    throw result.errors[0].message;
                }
            } catch (err) {
                const message =
                    typeof err == "string" ? err : "Ошибка авторизации";
                noty("error", message);
            }
        },

        async changePassword() {
            try {
                let response = await fetch("/api/auth/basic/restore", {
                    method: "POST",
                    body: JSON.stringify({
                        token: this.restoreToken,
                        id: this.id,
                        password: this.password
                    })
                });
                if (response.ok) {
                    this.switchMode("login");
                    noty("success", "Пароль успешно изменен");
                } else {
                    result = await response.json();
                    throw result.errors[0].message;
                }
            } catch (err) {
                const message =
                    typeof err == "string" ? err : "Неизвестная ошибка";
                noty("error", message);
            }
        },

        async requestRestore() {
            try {
                let response = await fetch("/api/auth/basic/restore/request", {
                    method: "POST",
                    body: JSON.stringify({
                        email: this.email
                    })
                });
                if (response.ok) {
                    this.switchMode("info");
                } else {
                    result = await response.json();
                    throw result.errors[0].message;
                }
            } catch (err) {
                const message =
                    typeof err == "string" ? err : "Неизвестная ошибка";
                noty("error", message);
            }
        }
    },

    async created() {
        let urlParams = new URL(window.location.href).searchParams;
        let mode = urlParams.get("mode");
        let token = urlParams.get("token");
        let id = urlParams.get("id");
        let result = null;
        if (!(token && id && mode)) return;
        else {
            if (localStorage.getItem("jwt")) {
                noty("error", "Ошибка");
                return;
            }
        }
        this.mode = "loading";
        if (mode == "confirm") {
            try {
                let response = await fetch("/api/auth/basic/confirm", {
                    method: "POST",
                    body: JSON.stringify({
                        token: token,
                        id: id
                    })
                });
                if (response.ok) {
                    this.switchMode("success");
                } else {
                    result = await response.json();
                    throw result.errors[0].message;
                }
            } catch (err) {
                const message =
                    typeof err == "string" ? err : "Неизвестная ошибка";
                noty("error", message);
            }
        } else if (mode == "restore") {
            try {
                let response = await fetch("/api/auth/basic/valid", {
                    method: "POST",
                    body: JSON.stringify({
                        token: token,
                        id: id
                    })
                });
                if (response.ok) {
                    this.switchMode("restore");
                    this.restoreToken = token;
                    this.id = id;
                } else {
                    result = await response.json();
                    throw result.errors[0].message;
                }
            } catch (err) {
                const message =
                    typeof err == "string" ? err : "Неизвестная ошибка";
                noty("error", message);
            }
        }
    }
};
</script>


<style scoped lang="scss">
.debug {
    position: absolute;
    top: 20px;
    left: 20px;
    display: flex;
    z-index: 9999;

    .switch {
        color: white;
        background-color: red;
    }
}
</style>
