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


-----------------------------



##what was done:-
- Used JWT fo API-authentication.
- Only the approved posts & approved comments will be retrievable.(another solution is softDeleting them)
- Add the posts count & comments count to allposts JSON response(approved only)
- TextModerator was slightly modified (as the three constructor arguments are constants based on every app)
- Created a Job to drastically reduce the response time of creating Posts and Comments
- Unread notifications could be retrieved route api/notifications
- added test cases to verify everything (usign `composer test` )




