<template>
  <div class="app">
    <AppHeader/>
    <div class="app-body">
      <Sidebar/>
      <main class="main">
        <breadcrumb :list="list"/>
        <div class="container-fluid">
		 <keep-alive >
          <router-view ></router-view>
		  </keep-alive>
        </div>
      </main>
      <AppAside/>
    </div>
    <AppFooter/>
  </div>
</template>
<script>
export default {
  
}
</script>