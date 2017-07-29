<template>
    <h3 v-for="passwords in SortedPasswords">{{ passwords.password }} - </h3>
</template>

<script>
    export default {
        methods:{
            listenForChanges(){
                Echo.channel('passwords')
                    .listen('UpdateNextPasswords', (e) => {
                        var password = this.passwords.find
                    })
            }
        }
    }
</script>