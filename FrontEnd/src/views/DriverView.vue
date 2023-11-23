<template lang="">
    <div class="mt-0 sm:mx-auto sm:w-full sm:max-w-sm">
        <div>
            <div  class="sm:mx-auto sm:w-full sm:max-w-sm">
                <h2 class="mt-2 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Enter driver details </h2>
            </div>
          <form class=" mt-11 space-y-6" action="#" @submit.prevent="handelSaveDriver" >
            <div>
                <!-- name -->
              <div class="mt-3">
                <input v-model="driverData.name"  id="name" placeholder="Full Name" name="license_plate" type="text"  required="" class="block w-full rounded-md border-1 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6" />
                </div>
            </div>
            <!-- years -->
            <div>

              <div class="mt-3">
                <input v-model="driverData.years" id="years" placeholder="Car Years" name="years" type="number"  required="" class="block w-full rounded-md border-1 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6" />
                </div>
            </div>
            <!-- make -->
            <div>
            <div class="mt-3">
                <input v-model="driverData.make" id="make" placeholder="make" name="make" type="text"  required="" class="block w-full rounded-md border-1 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6" />
                </div>
            </div>
            <!-- model -->
            <div>
            <div class="mt-3">
                <input v-model="driverData.model" id="model" placeholder="Model" name="model" type="text" required="" class="block w-full rounded-md border-1 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6" />
                </div>
            </div>
            <!-- color -->
            <div>
            <div class="mt-3">
                <input v-model="driverData.color" id="color" placeholder="Color" name="color" type="text" required="" class="block w-full rounded-md border-1 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6" />
                </div>
            </div>
            <!-- license_plate -->
            <div>
            <div class="mt-3">
                <input v-model="driverData.license_plate" id="license_plate" placeholder="License Plate#" name="license_plate" type="text"  required="" class="block w-full rounded-md border-1 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6" />
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" @submit.prevent="handelSaveDriver"  class=" rounded-md  px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline " style="background-color: black;">continue</button>
            </div>
          </form>
        </div>

    </div>
</template>
<script setup>
import http from '../Helpers/Http';
import { useRouter } from 'vue-router';
import {reactive} from 'vue'

const router = useRouter()
const driverData =reactive({
    name:'',
    years:null,
    make:'',
    model:'',
    color:'',
    license_plate:'',


})
const handelSaveDriver = ()=>{
    http().post('/api/driver',driverData).then((response)=>{
        router.push({
            name:'standby'
        })
    }).catch((error)=>{
        console.log(error.message)
    })
    console.log(driverData)
}

</script>
<style lang="">

</style>
