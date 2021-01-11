export class burgerToogler{
    constructor(){
        this.tooglers = { 
            '.menu__btn': 'menu__btn_active', 
            '.header__popup': 'header__popup_show', 
            'body': 'lock__scroll',
        };
    }

    toogle = () => {
        for (let [key, value] of Object.entries(this.tooglers)){
            document.querySelector(key).classList.toogle(value)
        }
    }
}

export class searchToogler{
    constructor(){
        this.tooglers = {
            '.search__box' : 'active',
            '.input__search' : 'input__active',
            '.search__btn' : 'search__btn_active',
            '.cancel__btn' : 'cancel__btn_active',
            '.phone' : 'phone__hidden',
            '.profile' : 'profile__hidden',
            '.nav' : 'nav__hidden',
        }
        document.querySelector('.search__btn').onclick = this.show
        document.querySelector('.cancel__btn').onclick = this.close
    }

    show = () => {
        for (let [key, value] of Object.entries(this.tooglers)){
            document.querySelector(key).classList.add(value)
        }      
    }

    close = () => {
        for (let [key, value] of Object.entries(this.tooglers)){
            document.querySelector(key).classList.remove(value)
        }      
    }
}

export class mobileSearchToogler extends searchToogler{
    constructor(){
        this.tooglers = {
            '.search__box_mobile' : 'active',
            '.input__search_mobile' : 'input__active',
            '.search__btn_mobile' : 'search__btn_active',
            '.cancel__btn_mobile' : 'cancel__btn_active',
            '.block__first' : 'block__first_hidden',
            '.mobile__block' : 'mobile__block_active',
            '.block__second' : 'block__second_active',
        }
        document.querySelector('.search__btn_mobile').onclick = this.show
        document.querySelector('.cancel__btn_mobile').onclick = this.close
    }
}