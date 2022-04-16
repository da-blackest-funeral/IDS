<template>
    <div class="mt-4">
        <h1 class="h3">
            <strong> Добавить товар</strong>
        </h1>
        <p class="h4 mt-3">Заказ</p>
        <div class="container-fluid bg-light mt-4 pb-5">
            <form method="POST" class="form-group pt-1">
                <slot></slot>
                <input type="hidden" name="order_id" />
                <div class="row mt-3">
                    <div class="col-12 col-md-3">
                        <label class="mb-1 mt-2 mt-md-0" for="height"
                            >Высота (длина)</label
                        >
                        <input
                            id="height"
                            name="height"
                            placeholder="Высота (габаритн.) в мм."
                            type="text"
                            class="form-control"
                            required
                        />
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="mb-1 mt-2 mt-md-0" for="width"
                            >Ширина (глубина)</label
                        >
                        <input
                            id="width"
                            name="width"
                            placeholder="Ширина (габаритн.) в мм."
                            type="text"
                            class="form-control"
                            required
                        />
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="mb-1 mt-2 mt-md-0" for="count"
                            >Количество (шт)</label
                        >
                        <input
                            id="count"
                            name="count"
                            placeholder="Количество (шт)"
                            type="text"
                            class="form-control"
                            required
                        />
                    </div>
                </div>
                <div id="items-row" class="row mt-4">
                    <div
                        id="categories-container"
                        class="col-10 col-md-3 mt-2 mt-md-0"
                    >
                        <order-form-product-type></order-form-product-type>
                    </div>
                    <transition name="fade">
                        <div
                            v-show="store.productOptions"
                            id="items"
                            class="col-10 col-md-3 mt-1"
                        >
                            <order-form-second-select></order-form-second-select>
                        </div>
                    </transition>
                    <div
                        v-show="store.profiles"
                        id="third"
                        class="col-10 col-md-3 mt-2 mt-md-0"
                    >
                        <order-form-third-select></order-form-third-select>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import OrderFormProductType from "./OrderFormProductType";
import OrderFormSecondSelect from "./OrderFormSecondSelect";
import { orderFormStore } from "../stores/store";
import OrderFormThirdSelect from "./OrderFormThirdSelect";

export default {
    name: "OrderForm",
    components: {
        OrderFormThirdSelect,
        OrderFormSecondSelect,
        OrderFormProductType,
    },
    setup() {
        const store = orderFormStore();

        return {
            store,
        };
    },
};
</script>

<style scoped></style>
