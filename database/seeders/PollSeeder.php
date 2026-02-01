<?php

namespace Database\Seeders;

use App\Models\Poll;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::count() < 3 ? [
            User::create([
                'name' => 'Alice',
                'email' => 'alice@test.test',
                'password' => bcrypt('password'),
            ]),
            User::create([
                'name' => 'Bob',
                'email' => 'bob@test.test',
                'password' => bcrypt('password'),
            ]),
            User::create([
                'name' => 'Charlie',
                'email' => 'charlie@test.test',
                'password' => bcrypt('password'),
            ]),
        ] : User::take(3)->get();

        $polls = Poll::count() < 5 ? [
            Poll::create([
                'title' => 'What colors do you like?',
                'description' => 'Choose one from the options below.',
                'user_id' => $users[0]->id,
            ]),
            Poll::create([
                'title' => 'What colors do you hate?',
                'description' => 'Choose one from the options below.',
                'user_id' => $users[1]->id,
            ]),
            Poll::create([
                'title' => 'What where the last colors you saw?',
                'description' => 'Pick one from the list.',
                'user_id' => $users[2]->id,
            ]),
            Poll::create([
                'title' => 'Which colors could you never wear?',
                'description' => 'Select the colors you dislike the most.',
                'user_id' => null,
            ]),
            Poll::create([
                'title' => 'Which colors would you choose for your new car?',
                'description' => 'Select your favorite color for a car.',
                'user_id' => $users[1]->id,
            ]),
        ] : Poll::take(5)->get();

        $pollOptions = [
            'Red',
            'Blue',
            'Green',
            'Yellow',
            'Black',
            'White',
            'Purple',
            'Orange',
        ];

        // only take a subset of options for each poll
        foreach ($polls as $poll) {
            $optionsForPoll = array_slice($pollOptions, 0, rand(3, 6));
            foreach ($optionsForPoll as $index => $optionText) {
                $poll->options()->create([
                    'option_text' => $optionText,
                    'order' => $index,
                ]);
            }
        }

        // Create random votes for each poll option
        // each person has to vote on each option
        // null user_id means anonymous vote
        $voteTypes = ['yes', 'no', 'maybe'];
        foreach ($polls as $poll) {
            $options = $poll->options;
            foreach ($options as $option) {
                foreach ($users as $user) {
                    $poll->votes()->create([
                        'poll_option_id' => $option->id,
                        'user_id' => rand(0, 1) ? $user->id : null,
                        'voter_name' => $user->name,
                        'vote_type' => $voteTypes[array_rand($voteTypes)],
                    ]);
                }
            }
            // add some anonymous votes
            for ($i = 0; $i < rand(5, 10); $i++) {
                $voterName = $this->generateRandomVoterName();
                foreach ($options as $option) {
                    $poll->votes()->create([
                        'poll_option_id' => $option->id,
                        'user_id' => null,
                        'voter_name' => $voterName,
                        'vote_type' => $voteTypes[array_rand($voteTypes)],
                    ]);
                }
            }
        }
    }

    private
    function generateRandomVoterName(): string
    {
        // names that are not in the users table
        $names = [
            'Guest123',
            'Anonymous456',
            'Visitor789',
            'UserXYZ',
            'RandomName',
            'PollParticipant',
            'ColorLover',
            'VoteMaster',
        ];
        return $names[array_rand($names)];
    }
}
