<script setup>
import { reactive } from 'vue';
import { useAuth } from '../composables/useAuth';
import BaseInput from '../components/ui/BaseInput.vue';
import BaseButton from '../components/ui/BaseButton.vue';

const { login, isLoading, errorMessage } = useAuth();
const form = reactive({ username: '', password: '' });
const handleLogin = () => login(form.username, form.password);
</script>

<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="w-full max-w-[350px] bg-white shadow-sm border border-gray-200">
      <div class="bg-[#2a3f5a] p-3 text-center">
        <h1 class="text-white text-base font-semibold tracking-wide">Pharmacovigilance</h1>
      </div>

      <div class="p-6">
        <form @submit.prevent="handleLogin" class="space-y-4">
          <div v-if="errorMessage" class="bg-red-100 text-red-600 p-2 text-xs text-center border border-red-300">
            {{ errorMessage }}
          </div>
          
          <BaseInput 
            v-model="form.username" 
            placeholder="Username" 
            required 
          />

          <BaseInput 
            v-model="form.password" 
            type="password"
            placeholder="Password" 
            required 
          />

          <BaseButton 
            type="submit" 
            :loading="isLoading" 
            variant="primary" 
            class="w-full mt-2"
            text="Login"
          />
        </form>
      </div>
    </div>
  </div>
</template>
