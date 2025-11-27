<template>
  <div>
    <FileUpload />
    <AdminsideBar/>
  </div>
</template>

<script>
import AdminsideBar from '@/Components/AdminsideBar.vue';
import FileUpload from '../Components/FileUpload.vue'

export default {
  components: {
    FileUpload,
  }
}
</script>
