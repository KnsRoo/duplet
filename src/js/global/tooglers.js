export class burgerToogler{
    constructor(){
        this.tooglers = { 
            '.menu__btn': 'menu__btn_active', 
            '.header__popup': 'header__popup_show', 
            'body': 'lock__scroll',
        };
        document.querySelector('.menu__btn').onclick = this.toogle
    }

    toogle = () => {
        for (let [key, value] of Object.entries(this.tooglers)){
            document.querySelector(key).classList.toggle(value)
        }
    }
}

export class searchToogler{
    constructor(selectors,tooglers){
        this.selectors = selectors
        this.tooglers = tooglers

        document.querySelector(this.selectors.searchBtn).onclick = this.show
        document.querySelector(this.selectors.cancelBtn).onclick = this.close

        document.querySelector(this.selectors.input).addEventListener('keyup', (e) => {
            if (e.key === 'Enter'){
                this.goSearch()
            }
        })
    }

    get isActive(){
        return document.querySelector(this.selectors.box).classList.contains('active')
    }

    goSearch = () => {
        let value = document.querySelector(this.selectors.input).value
        if (value.length > 3){
            window.location.href = `/catalog?query=${value}`
        }
    }

    show = () => {
        if (this.isActive){
            this.goSearch()
        } else {
            for (let [key, value] of Object.entries(this.tooglers)){
                document.querySelector(key).classList.add(value)
            }  
        }    
    }

    close = () => {
        for (let [key, value] of Object.entries(this.tooglers)){
            document.querySelector(key).classList.remove(value)
        }      
    }
}