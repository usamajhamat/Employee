import { createStore } from "vuex";
import bank from "@/services/store/bank";
import cart from "@/services/store/cart";
import category from "@/services/store/category";
import customer from "@/services/store/customer";
import paymentMethod from "@/services/store/paymentMethod";
import animal from "@/services/store/animal";
import purchase from "@/services/store/purchase";
import sale from "@/services/store/sale";
import supplier from "@/services/store/supplier";
import unit from "@/services/store/unit";
import supplierPayment from "@/services/store/supplierPayment";
import customerPayment from "@/services/store/customerPayment";
import auth from "@/services/store/auth";
import audit from "@/services/store/audit";
import expense from "@/services/store/expense";
import expenseCategory from "@/services/store/expenseCategory";
import directEntry from "@/services/store/directEntry";
import bankTransaction from "@/services/store/bankTransaction";
import fileUploader from "@/services/store/fileUploader";
import feed from "@/services/store/feed";
import weight from "@/services/store/weight";
import feedDose from "@/services/store/feedDose";
import tanazaApi from "@/services/store/tanazaApi";
import employee from "@/services/store/employee";
import company from "@/services/store/company";


export default createStore({
    modules: {
        fileUploader: fileUploader,
        auth: auth,
        category: category,
        animal: animal,
        weight:weight,
        bank: bank,
        bankTransaction: bankTransaction,
        unit: unit,
        customer: customer,
        supplier: supplier,
        paymentMethod: paymentMethod,
        sale: sale,
        // purchase: purchase,
        cart: cart,
        customerPayment: customerPayment,
        supplierPayment: supplierPayment,
        audit: audit,
        expense: expense,
        expenseCategory: expenseCategory,
        directEntry: directEntry,
        feed: feed,
        feedDose: feedDose,
        tanazaApi: tanazaApi,

        employee: employee,
        company: company,
    
    },
});
