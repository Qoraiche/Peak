<script setup>
import ExportTable from "@peak/Drawers/Admin/ExportTable.vue";
import ViewTransaction from "@peak/Drawers/Admin/ViewTransaction.vue";
import ChangeSubscriptionPlan from "@peak/Drawers/Admin/ChangeSubscriptionPlan.vue";
import ManageRole from "@peak/Drawers/Admin/ManageRole.vue";
import EditBlogCategory from "@peak/Drawers/Admin/EditBlogCategory.vue";
import CreateBlogCategory from "@peak/Drawers/Admin/CreateBlogCategory.vue";
import CreateNewLanguage from "@peak/Drawers/Admin/CreateNewLanguage.vue";
import CreateLanguageTranslationLine from "@peak/Drawers/Admin/CreateLanguageTranslationLine.vue";
import EditLanguage from "@peak/Drawers/Admin/EditLanguage.vue";
import EditUserPassword from "@peak/Drawers/Admin/EditUserPassword.vue";
import CreateDocCategory from "@peak/Drawers/Admin/CreateDocCategory.vue";
import EditDocCategory from "@peak/Drawers/Admin/EditDocCategory.vue";
import ManagePermission from "@peak/Drawers/Admin/ManagePermission.vue";
import ViewReport from "@peak/Drawers/Admin/ViewReport.vue";
import Drawers from "@/Drawers/Admin/Drawers.vue";
</script>

<template>
<!--  <ViewTransaction/>-->
<!--  <ChangeSubscriptionPlan/>-->
  <ManageRole/>
  <ManagePermission/>
  <EditBlogCategory/>
<!--  <EditDocCategory/>-->
  <CreateBlogCategory/>
<!--  <CreateDocCategory/>-->
  <ManageRole/>
<!--  <CreateNewLanguage/>-->
<!--  <CreateLanguageTranslationLine/>-->
<!--  <EditLanguage/>-->
  <EditUserPassword/>
<!--  <ViewReport/>-->
  <Drawers/>
</template>

<style scoped>

</style>
