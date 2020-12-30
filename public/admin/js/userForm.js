"use strict";

class UserForm {

    constructor(node){
    
        this.node = node;
        this.userId = node.id;
        this.categoryInput = node.querySelector(".category-input");
        this.chainInput = node.querySelector(".chain-input");
        this.formSubmit = node.querySelector(".form-submit");
        this.formData = {};
        this.formData["userId"] = this.userId;
        this.createEvents(this.categoryInput, this.chainInput, this.formSubmit);

    
    }

    createEvents(categoryInput, chainInput, formSubmit) {
        categoryInput.itemType = "category";
        categoryInput.formData = this.formData;
        chainInput.itemType = "chain";
        chainInput.formData = this.formData;
        formSubmit.formData = this.formData;
        categoryInput.addEventListener("input", this.getItemId);
        chainInput.addEventListener("input", this.getItemId);
        formSubmit.addEventListener("click", this.sendForm);
    }

    getItemId() {
        let item = event.target;
        let formData = item.formData;
        let formField = `${item.itemType}Id`;
        let itemList = item.list;
        let value = item.value;
        let listOptions = itemList.options;
        Array.from(listOptions).forEach(option => {
            if(option.value === value){
                formData[formField] = option.id;
            }
        });
    }

    async sendForm() {
        event.preventDefault(); 
        let formData = event.target.formData;
        let body = JSON.stringify(formData);
        let response = await fetch("/admin/user/update-groups", {
            method: 'PUT',
            body: body,
        });
    }

}

let chainsCategoryForms = document.querySelectorAll(".chains-category-form");

chainsCategoryForms.forEach(form => {
    new UserForm(form);
});
