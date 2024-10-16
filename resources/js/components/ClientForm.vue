<template>
    <div>
        <h1 class="mb-6">Clients -> Add New Client</h1>

        <div class="max-w-lg mx-auto">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" class="form-control" v-model="client.name">
                <span style="color: red;" v-if="errors.name">{{ errors.name }}</span>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" class="form-control" v-model="client.email">
                <span style="color: red; " v-if="errors.email">{{ errors.email }}</span>
                <span style="color: red; " v-if="errors['contact']">{{ errors['contact'] }}</span>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" class="form-control" v-model="client.phone">
                <span style="color: red; " v-if="errors.phone">{{ errors.phone }}</span>
                <span style="color: red; " v-if="errors['contact']">{{ errors['contact'] }}</span>
            </div>
            <div class="form-group">
                <label for="name">Address</label>
                <input maxlength="100" type="text" id="address" class="form-control" v-model="client.address">
            </div>
            <div class="flex">
                <div class="form-group flex-1">
                    <label for="city">City</label>
                    <input maxlength="50" type="text" id="city" class="form-control" v-model="client.city">
                </div>
                <div class="form-group flex-1">
                    <label for="postcode">Postcode</label>
                    <input maxlength="6" type="text" id="postcode" class="form-control" v-model="client.postcode">
                </div>
            </div>

            <div class="text-right">
                <a href="/clients" class="btn btn-default">Cancel</a>
                <button @click="storeClient" class="btn btn-primary">Create</button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import * as Yup from 'yup';


export default {
    name: 'ClientForm',

    data() {
        return {
            client: {
                name: '',
                email: '',
                phone: '',
                address: '',
                city: '',
                postcode: '',
            },
            errors: {},
        }
    },

    created() {

    },

    methods: {
        async validate() {
            this.errors = {};
            const schema = Yup.object().shape({
                name: Yup.string()
                    .required('The name is required and must be up to 190 characters long')
                    .max(190),
                phone: Yup.string()
                    .nullable()
                    .matches(/^[\d\s+]*$/, 'Phone can only contain digits, spaces, and a plus sign'),
                email: Yup.string()
                    .email('The email must be a valid email address')
                    .nullable(),
            }).test('contact', 'At least one of phone or email is required', function (value) {
                const {email, phone} = value;
                if (!email && !phone) {
                    return this.createError({path: 'contact', message: 'At least one of phone or email is required'});
                }
                return true;
            });

            try {
                await schema.validate(this.client, {abortEarly: false});
            } catch (err) {

                err.inner.forEach(error => {
                    this.$set(this.errors, error.path, error.message);
                });
            }
        },

        async storeClient() {
            await this.validate();
            if (Object.keys(this.errors).length === 0) {
                axios.post('/clients', this.client)
                    .then((data) => {
                        window.location.href = data.data.url;
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }
        }
    }
}
</script>
