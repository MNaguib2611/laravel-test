# Laravel Interview Test

### Endpoints
- [ ] Get all posts (include their user).
- [ ] Show a single post (include user and comments).
- [ ] Create a post.
- [ ] Comment on a post.
- [x] Get all notifications of the logged in user.

### Notes:
- Always exclude rejected posts and comments from responses.
- When showing a single post, if it is rejected, return a 404 response.
- Test posts creation. (post is pending by default, posts with bad words are rejected, posts with no bad words are approved)
- Text moderation service `.env` credentials:
    `SIGHT_ENGINE_API_USER=1223288733`
    `SIGHT_ENGINE_API_SECRET=VXHMa5Rwxgpodphnkmv4`
- use `composer test` to run the tests.
