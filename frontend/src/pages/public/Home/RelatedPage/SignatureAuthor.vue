<template >
    <div class="signature__wrapper">
        <div v-if="authorStore.isLoading" class="loading">
            <LoadingSmall />
        </div>
        <div class="signature__user">
            <router-link :to="`/profile/${author?.id}`">
                <div class="signature__user__left">
                    <img class="signature__user__img" :src="'http://127.0.0.1:8000/images/avatar/' + author?.profile_image"
                        alt="" v-if="author?.profile_image">
                    <img class="signature__user__img" src="../../../../assets/images/avatar-default.png" alt="" v-else>
                    <div class="signature__user__infor">
                        <span class="signature__user__author">{{ author?.name }}</span>
                        <span class="signature__user__nickname">{{ author?.slug }}</span>
                    </div>
                </div>
            </router-link>

            <div class="signature__user__right">
                <button class="signature__user__btn signature__user__btn--follow " v-if="!handleCheckFollow()"
                    @click="handleToggleFollow(author?.id)">Theo
                    dõi
                    <ion-icon name="person-add-outline"></ion-icon>
                </button>
                <button class="signature__user__btn signature__user__btn--followed" v-else
                    @click="handleToggleFollow(author?.id)">Đang theo dõi
                    <ion-icon name="checkmark-circle-outline"></ion-icon>
                </button>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, onMounted } from "vue"
import { useAuthorStore } from "../../../../stores/authorStore";
// import LoadingSmall from "../../../../components/LoadingSmall.vue"
const authorStore = useAuthorStore()
const props = defineProps({
    author: Object,
    isFollowing: Boolean,
})
const handleFetchUserFollower = async () => {
    await authorStore.fetchAllBlogger()
}
const userData = ref(JSON.parse(localStorage.getItem("user")));
// console.log("f", props.author.is_following);
// console.log("w", props.author.is_follower);
console.log("===", props.author.is_follower);
const handleCheckFollow = () => {
    if (props.isFollowing) {
        return true
    } else if (props.author.is_follower == 1) {
        return true
    } else if (props.author.is_follower == 0) {
        return false
    } else {
        if (props.author?.follows?.length > 0) {
            const result = props.author.follows.map((user) => user.follower_id).includes(userData.value?.id);
            if (result === true) {
                return true
            } else {
                return false
            }
        } else {
            return false
        }
    }
}

const handleCheckCheck = (check) => {
    console.log(check);
}

const handleToggleFollow = (id) => {
    authorStore.getFollowAuthor(userData.value.id, id)
}

</script>
<style lang="scss" scoped>
.loading {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 20px;
}

.signature__wrapper {
    width: 100%;

    .signature__user {
        margin-top: 20px;
        display: flex;
        justify-content: space-between;

        .signature__user__left {
            display: flex;
            gap: 10px;

            .signature__user__infor {
                display: flex;
                flex-direction: column;
                font-size: 15px;
                gap: 5px;

                .signature__user__author {
                    font-weight: 700;
                }

                .signature__user__nickname {
                    font-size: 14px;
                    font-weight: 400;
                    color: #6a6666;
                }
            }
        }

        .signature__user__img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            margin-right: 0;
            border-radius: 50%;
        }

        .signature__user__btn {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
            border-radius: 10px;
            height: 36px;
            max-width: 150px;
            gap: 5px;
            cursor: pointer;
            background-image: linear-gradient(to right bottom, #2ebac1, #a4d96c);
            color: var(--white-color);
            font-size: 15px;

            &--follow {
                background-image: linear-gradient(to right bottom, #2ebac1, #a4d96c);
                color: var(--white-color);

            }

            &--followed {
                border: 1px solid var(--border-color);
                color: var(--green-color);
                font-weight: 700;
                background-image: var(--white-color) !important;
            }
        }
    }
}
</style>