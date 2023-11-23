
<template>
    <div class="mt-0 sm:mx-auto sm:w-full sm:max-w-sm">
        <div v-if="!waitingFortification">
            <div  class="sm:mx-auto sm:w-full sm:max-w-sm">
                <h2 class="mt-2 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Enter your Email</h2>
              </div>
          <form class=" mt-11 space-y-6" action="#" @submit.prevent="handelLogin">
            <div>
              <div class="mt-3">
                <input id="email" placeholder="Email address" name="email" v-model="data.email" type="email" autocomplete="email" required="" class="block w-full rounded-md border-1 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6" />              </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" @submit.prevent="handelLogin"  class=" rounded-md  px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline " style="background-color: black;">continue</button>
            </div>
          </form>
        </div>
        <div v-else>
            <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                <h2 class="mt-2 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Enter code</h2>
              </div>
          <form class=" mt-11 space-y-6" action="#" @submit.prevent="handelVerification">
            <div>
                <div class="mt-3">
                    <input id="code" placeholder="••••••" name="number" v-model="data.login_code" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="6" minlength="6" required="" class="block w-full rounded-md border-1 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6 text-center" />
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" @submit.prevent="handelVerification"  class=" rounded-md  px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline " style="background-color: black;">continue</button>
            </div>
          </form>
        </div>
    </div>
</template>
<script setup>
import { reactive ,ref ,onMounted,computed} from 'vue'
import axios from 'axios'
import { vMaska } from "maska"
import { useRouter } from 'vue-router';

onMounted(() => {
    if(localStorage.getItem('token')){

        router.push({
            name:'landing',
        })
}
});

const router = useRouter()
const waitingFortification = ref(false)
const data  = reactive({
                        email : null,
                        login_code:null
                      })

const handelLogin =() =>{
         axios.post('http://127.0.0.1:8000/api/login', data)
         .then((response)=>{
         console.log(response.data);
         waitingFortification.value = true

         }).catch(
            (error)=>{console.log(error)
            alert(error.response.data.message)
        });
}
const format = computed(()=>{
    return{
        email: data.email,
        login_code: data.login_code
    }})
const handelVerification = () => {
    console.log(data.login_code)
    axios.post('http://127.0.0.1:8000/api/login-verify', {
        email: data.email,
        code: data.login_code
    })
         .then((response)=>{
         console.log('login',response.data['token']);
         console.log('login data',response.data);
         localStorage.setItem('token', response.data['token']);
         router.push({
            name:'landing',
         })
             }).catch(
            (error)=>{console.log(error)
            alert(error.response.data.message)
        });

}

</script>
<style lang="">

</style>
