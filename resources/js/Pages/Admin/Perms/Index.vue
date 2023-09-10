<script setup>
import {
  MDBCard,
  MDBCardHeader,
  MDBCardBody,
  MDBTable,
} from "mdb-vue-ui-kit";

import { router } from "@inertiajs/vue3";

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    roles: Object,
    perms: Object
});

const onClickOption = (e,role,perm) => {
    const $td = e.target;

    const data = {
        'id_role' : role.id,
        'id_perm' : perm.id,
    }

    router.post(route('admin.perms.save'), data, {
        success: function(){
            $td.innerText = ($td.innerText) ? '': 'X';
        }
    })
}
</script>

<template>
    <AuthenticatedLayout>
       <div class="mt-4 mx-4">
            <MDBCard>
                <MDBCardHeader class="py-4 mb-3">
                    <h4 class="mb-0">Permisos</h4>
                </MDBCardHeader>
                <MDBCardBody>
                    <MDBTable border id="tb-perms">
                        <thead>
                            <tr>
                                <th >Permisos/Roles</th>
                                <template v-for="role in roles" :key="role.id">
                                    <th class="text-nowrap">{{role.description}}</th>
                                </template>
                            </tr>
                        </thead>

                        <tbody>
                            <template v-for="perm in perms" :key="perm.id">
                                <tr>
                                    <td class="bg-primary text-white" nowrap>{{perm.description}}</td>
                                    <template v-for="role in roles" :key="role.id">
                                        <td class="text-center text-danger fw-bold fs-6"
                                            @click="onClickOption($event,role,perm)"
                                        >{{ role?.permissions.some(option => option.id == perm.id)  ? 'X':'' }}</td>
                                    </template>
                                </tr>
                            </template>
                        </tbody>
                    </MDBTable>
                </MDBCardBody>
            </MDBCard>

        </div>
    </AuthenticatedLayout>
</template>
