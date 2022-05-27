<template>
    <p>test: {{ test }}</p>
    <div v-for="quiz in quizzes">
        <p>{{ quiz.title }}</p>
    </div>
</template>

<script>
export default {
    name: "Welcome.vue",
    props: {
        quizzes: Array,
        name: String,
        test: String,
    }
}
</script>

<style scoped>

</style>
