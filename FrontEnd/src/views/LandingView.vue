<template lang="">

    <div class="pt-16">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <h2 class="mt-2 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Landing</h2>
          </div>
        <div class="overflow-hidden sm:rounded-md max-w-sm mx-auto text-left">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex justify-between">
                    <button
                    @click="handelStartDriver"
                    type="submit"   class=" rounded-md  px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline " style="background-color: black;">Start Driver</button>

                    <button
                    @click="handelFindRide"
                    type="submit"   class=" rounded-md  px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline " style="background-color: black;">Find A Ride</button>

                </div>
            </div>
        </div>
    </div>

</template>
<script setup>
import { useRouter } from 'vue-router';
import http from '../Helpers/Http';

const router = useRouter()
const handelStartDriver = () =>{
    console.log('hello world')
    http().get('api/driver')
    .then((response)=>{
        if(response.data.driver){
            console.log('response.data.driver',response.data.driver)
            router.push({
                 name:'standby'
              })
        }else{
            router.push({
                 name:'driver'
              })
        }
    }
    ).catch((error)=>{
        console.error('errrrrroroorrrrrr',error)
    })

}

const handelFindRide = () =>{
    console.log('hello world')
    router.push({
            name:'location',
        })
}
</script>
<style lang="">

</style>
