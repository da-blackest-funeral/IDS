import { defineStore } from "pinia";
import axios from "axios";

export const orderFormStore = defineStore("orderForm", {
    state: () => ({
        categories: null,
        superCategories: null,
        categoryId: 0,
        productOptions: null,
        productLink: null,
        productName: null,
        productLabel: null,
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
        async fetchCategories() {
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
                this.productLink = link;
                this.productName = name;
                this.productLabel = label;
            } catch (err) {
                console.log(err);
            }
        },
    },
});
