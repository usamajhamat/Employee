import apiClient from "@/config/axios";
import { Save } from "lucide-vue-next";

export default {
    // auth
    login(params) {
        return apiClient.post("/login", params);
    },

    getUser() {
        return apiClient.get("/user");
    },

    logout() {
        return apiClient.post("/logout");
    },

    // file uploader
    getUploadedFiles(params) {
        return apiClient.get("/uploads", {
            params: params,
        });
    },

    uploadFile(formData) {
        return apiClient.post("/uploads", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
    },

    deleteUploadedFile(params) {
        return apiClient.delete("/uploads", {
            params: params,
        });
    },

    // categories
    getCategories(params) {
        return apiClient.get("/categories", {
            params: params,
        });
    },

    saveCategory(params) {
        return apiClient.post("/categories", params);
    },

    updateCategory(params) {
        return apiClient.put(`/categories/${params.id}`, params);
    },

    deleteCategories(params) {
        return apiClient.delete("/categories", {
            params: params,
        });
    },

    // Animals
    getAnimals(params) {
        return apiClient.get("/animals", {
            params: params,
        });
    },
    getAnimalStats(params) {
        return apiClient.get("/animal-stats", {
            params: params,
        });
    },

    saveAnimal(params) {
        return apiClient.post("/animals", params);
    },

    updateAnimal(params) {
        return apiClient.put(`/animals/${params.id}`, params);
    },

    deleteAnimals(params) {
        return apiClient.delete("/animals", {
            params: params,
        });
    },

    // banks
    getBanks(params) {
        return apiClient.get("/banks", {
            params: params,
        });
    },

    saveBank(params) {
        return apiClient.post("/banks", params);
    },

    updateBank(params) {
        return apiClient.put(`/banks/${params.id}`, params);
    },

    deleteBanks(params) {
        return apiClient.delete("/banks", {
            params: params,
        });
    },

    // bank transactions
    getBankTransactions(params) {
        return apiClient.get("/bank-transactions", {
            params: params,
        });
    },

    saveBankTransaction(params) {
        return apiClient.post("/bank-transactions", params);
    },

    deleteBankTransactions(params) {
        return apiClient.delete("/bank-transactions", {
            params: params,
        });
    },

    // units
    getUnits(params) {
        return apiClient.get("/units", {
            params: params,
        });
    },

    saveUnit(params) {
        return apiClient.post("/units", params);
    },

    updateUnit(params) {
        return apiClient.put(`/units/${params.id}`, params);
    },

    deleteUnits(params) {
        return apiClient.delete("/units", {
            params: params,
        });
    },

    // audits
    getAudits(params) {
        return apiClient.get("/audits", {
            params: params,
        });
    },

    saveAudit(params) {
        return apiClient.post("/audits", params);
    },

    deleteAudits(params) {
        return apiClient.delete("/audits", {
            params: params,
        });
    },

    // customers
    getCustomers(params) {
        return apiClient.get("/customers", {
            params: params,
        });
    },

    saveCustomer(params) {
        return apiClient.post("/customers", params);
    },

    updateCustomer(params) {
        return apiClient.put(`/customers/${params.id}`, params);
    },

    deleteCustomers(params) {
        return apiClient.delete("/customers", {
            params: params,
        });
    },

    // suppliers
    getSuppliers(params) {
        return apiClient.get("/suppliers", {
            params: params,
        });
    },

    saveSupplier(params) {
        return apiClient.post("/suppliers", params);
    },

    updateSupplier(params) {
        return apiClient.put(`/suppliers/${params.id}`, params);
    },

    deleteSuppliers(params) {
        return apiClient.delete("/suppliers", {
            params: params,
        });
    },

    // units
    getPaymentMethods(params) {
        return apiClient.get("/payment-methods", {
            params: params,
        });
    },

    savePaymentMethod(params) {
        return apiClient.post("/payment-methods", params);
    },

    updatePaymentMethod(params) {
        return apiClient.put(`/payment-methods/${params.id}`, params);
    },

    deletePaymentMethods(params) {
        return apiClient.delete("/payment-methods", {
            params: params,
        });
    },

    // sales
    getSales(params) {
        return apiClient.get("/sales", {
            params: params,
        });
    },

    saveSale(params) {
        return apiClient.post("/sales", params);
    },

    updateSale(params) {
        return apiClient.put(`/sales/${params.id}`, params);
    },

    deleteSales(params) {
        return apiClient.delete("/sales", {
            params: params,
        });
    },

    // purchases
    getPurchases(params) {
        return apiClient.get("/purchases", {
            params: params,
        });
    },

    savePurchase(params) {
        return apiClient.post("/purchases", params);
    },

    updatePurchase(params) {
        return apiClient.put(`/purchases/${params.id}`, params);
    },

    deletePurchases(params) {
        return apiClient.delete("/purchases", {
            params: params,
        });
    },

    // cart
    getCartItems(params) {
        return apiClient.get("/cart-items", {
            params: params,
        });
    },

    saveCartItem(params) {
        return apiClient.post("/cart-items", params);
    },

    updateCartItem(params) {
        return apiClient.put(`/cart-items/${params.id}`, params);
    },

    deleteCartItems(params) {
        return apiClient.delete("/cart-items", {
            params: params,
        });
    },

    // customer payments
    getCustomerPayments(params) {
        return apiClient.get("/customer-payments", {
            params: params,
        });
    },

    saveCustomerPayment(params) {
        return apiClient.post("/customer-payments", params);
    },

    deleteCustomerPayment(params) {
        return apiClient.delete("/customer-payments", {
            params: params,
        });
    },

    // supplier payments
    getSupplierPayments(params) {
        return apiClient.get("/supplier-payments", {
            params: params,
        });
    },

    saveSupplierPayment(params) {
        return apiClient.post("/supplier-payments", params);
    },

    deleteSupplierPayment(params) {
        return apiClient.delete("/supplier-payments", {
            params: params,
        });
    },

    // expenses
    getExpenses(params) {
        return apiClient.get("/expenses", {
            params: params,
        });
    },

    saveExpense(params) {
        return apiClient.post("/expenses", params);
    },

    updateExpense(params) {
        return apiClient.put(`/expenses/${params.id}`, params);
    },

    deleteExpenses(params) {
        return apiClient.delete("/expenses", {
            params: params,
        });
    },

    // categories
    getExpenseCategories(params) {
        return apiClient.get("/expense-categories", {
            params: params,
        });
    },

    saveExpenseCategory(params) {
        return apiClient.post("/expense-categories", params);
    },

    updateExpenseCategory(params) {
        return apiClient.put(`/expense-categories/${params.id}`, params);
    },

    deleteExpenseCategories(params) {
        return apiClient.delete("/expense-categories", {
            params: params,
        });
    },

    // direct entry
    saveDirectEntry(params) {
        return apiClient.post("/direct-entries", params);
    },

    //Feeds
    getFeeds(params) {
        return apiClient.get("/feeds", {
            params: params,
        });
    },

    saveFeed(params) {
        return apiClient.post("/feeds", params);
    },

    updateFeed(params) {
        return apiClient.put(`/feeds/${params.id}`, params);
    },

    deleteFeed(params) {
        return apiClient.delete("/feeds", {
            params: params,
        });
    },
    saveWeight(params) {
        return apiClient.post("/weights", params);
    },

    getWeights(params) {
        return apiClient.get("/weights", {
            params: params,
        });
    },

    saveFeedDose(params) {
        return apiClient.post("/feedDoses", params);
    },

    getFeedDoses(params) {
        return apiClient.get("/feedDoses", {
            params: params,
        });
    },



    getDeviceStatus(params) {
        return apiClient.get("https://cloud.tanaza.com/apis/v3.0/11F975B0CFD3F548776B8C9C738605D1/A9E3F295D529FABA248B0790DAA30D9F/status ", {
            params: params,
        });
    },


    saveTanazaAccount(params) {
        return apiClient.post("/tanaza-account", params);
    },

    saveTanazaNetwork(params) {
        return apiClient.post("/save-tanaza-network", params);
    },








    saveEmployee(params) {
        return apiClient.post("/save-employee", params);
    },

    getEmployees(params) {
        return apiClient.get("/get-employees", {
            params: params,
        });
    },

    deleteEmployee(params) {
        return apiClient.delete("/delete-employee", {
            params: params,
        });
    },
    updateEmployee(params) {
        return apiClient.post("/update-employee", params);
    },
    getEmployeeDetails(params) {
        return apiClient.get("/get-employee-details", {
            params: params,
        });
    },



    saveCompany(params) {
        return apiClient.post("/save-company", params);
    },

    updateCompany(params) {
        return apiClient.post("/update-company", params);
    },

    getCompnies(params) {
        return apiClient.get("/get-compnies", {
            params: params,
        });
    },

    getCompnyDetails(params) {
        return apiClient.get("/get-compny-details", {
            params: params,
        });
    },


    getDashboardAnalytics(params) {
        return apiClient.get("/get-dashboard-analytics", {
            params: params,
        });
    },

    deleteCompany(params) {
        return apiClient.delete("/delete-company", {
            params: params,
        });
    },


};
