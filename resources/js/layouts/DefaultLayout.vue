<script setup>
import Sidebar from "@/components/Sidebar.vue";
</script>

<template>
    <section>
        <Sidebar />
        <div class="sm:ml-72 min-h-screen">
            <div>
                <router-view ></router-view>
            </div>
        </div>
    </section>
</template>
