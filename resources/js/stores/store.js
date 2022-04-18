import { defineStore } from "pinia";
import axios from "axios";

export const orderFormStore = defineStore("orderForm", {
    state: () => ({
        categories: null,
        superCategories: null,
        categoryId: 0,
        productOptions: null,
        profileLink: null,
        productName: null,
        productLabel: "",
        additional: 0,
        profiles: null,
        profileId: 0,
    }),
    actions: {
        async fetchProductTypes() {
            try {
                const {
                    data: {
                        data: { ...categories },
                        superCategories: { ...superCategories },
                    },
                } = await axios.get("/api/categories");
                this.categories = categories;
                this.superCategories = superCategories;
            } catch (err) {
                console.log(err);
            }
        },
        // стандартизировать запросы что везде было const { data: dataInJSON
        // : { ...array} }
        // categories with fina component: 21 (накладка на подоконник)
        // categories with hided static selects : 22 (ремонт/аксессуары/услуги), подгрузить label
        // загрузить селект с типом пленки для 23 (пленка на окно)
        // для category 24 (Отлив) загрузить label для селекта
        // для category 25 (Откос) загрузить select
        // feature 1
        // вынести логику category 25 (Добавить позицию) в отдельную кнопку вне формы
        async fetchCategories() {
            this.productOptions = null;
            this.profiles = null;
            this.additional = 0;
            this.profileId = 0;
            try {
                const {
                    data: {
                        data: { ...options },
                        link,
                        name,
                        label,
                    },
                } = await axios.get("/api/product-options", {
                    params: {
                        categoryId: this.categoryId,
                    },
                });
                this.productOptions = options;
                this.profileLink = link;
                this.productName = name;
                this.productLabel = label;
            } catch (err) {
                console.log(err);
            }
        },
        async fetchProfiles() {
            try {
                const {
                    data: {
                        data: { ...profiles },
                    },
                } = await axios.get(this.profileLink, {
                    params: {
                        categoryId: this.categoryId,
                        additional: this.additional,
                    },
                });
                this.profiles = profiles;
            } catch (err) {
                console.log(err);
            }
        },
    },
});
