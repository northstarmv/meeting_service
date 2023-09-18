<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Chats
 *
 * @mixin Builder
 * @property string $chat_id
 * @property string $title
 * @property string $description
 * @property int $owner_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ClientChats[] $clients
 * @property-read int|null $clients_count
 * @property-read \App\Models\User|null $owner
 * @method static \Illuminate\Database\Eloquent\Builder|Chats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Chats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Chats query()
 * @method static \Illuminate\Database\Eloquent\Builder|Chats whereChatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chats whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chats whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chats whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chats whereUpdatedAt($value)
 */
	class Chats extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ClientChats
 *
 * @mixin Builder
 * @property int $id
 * @property string $chat_id
 * @property int $client_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Chats|null $chat
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|ClientChats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientChats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientChats query()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientChats whereChatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientChats whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientChats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientChats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientChats whereUpdatedAt($value)
 */
	class ClientChats extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\DoctorMeetings
 *
 * @mixin Builder
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $trainer_id
 * @property int|null $client_id
 * @property int $doctor_id
 * @property string $start_time
 * @property string|null $meeting_id
 * @property string|null $passcode
 * @property int $approved
 * @property int $has_rejected
 * @property string|null $rejected_reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|DoctorMeetings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DoctorMeetings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DoctorMeetings query()
 * @method static \Illuminate\Database\Eloquent\Builder|DoctorMeetings whereApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DoctorMeetings whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DoctorMeetings whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DoctorMeetings whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DoctorMeetings whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DoctorMeetings whereHasRejected($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DoctorMeetings whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DoctorMeetings whereMeetingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DoctorMeetings wherePasscode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DoctorMeetings whereRejectedReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DoctorMeetings whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DoctorMeetings whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DoctorMeetings whereTrainerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DoctorMeetings whereUpdatedAt($value)
 */
	class DoctorMeetings extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\MeetingTrainerClients
 *
 * @mixin Builder
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $trainer_id
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\TrainerClientsMeetingData[] $clients
 * @property string $start_time
 * @property string $meeting_id
 * @property string $passcode
 * @property bool $finished
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read int|null $clients_count
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingTrainerClients newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingTrainerClients newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingTrainerClients query()
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingTrainerClients whereClients($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingTrainerClients whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingTrainerClients whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingTrainerClients whereFinished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingTrainerClients whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingTrainerClients whereMeetingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingTrainerClients wherePasscode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingTrainerClients whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingTrainerClients whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingTrainerClients whereTrainerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MeetingTrainerClients whereUpdatedAt($value)
 */
	class MeetingTrainerClients extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TrainerClientsMeetingData
 *
 * @mixin Builder
 * @property int $id
 * @property int $meeting_id
 * @property int $trainer_id
 * @property int $client_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\MeetingTrainerClients|null $meetings
 * @method static \Illuminate\Database\Eloquent\Builder|TrainerClientsMeetingData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainerClientsMeetingData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainerClientsMeetingData query()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainerClientsMeetingData whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainerClientsMeetingData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainerClientsMeetingData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainerClientsMeetingData whereMeetingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainerClientsMeetingData whereTrainerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainerClientsMeetingData whereUpdatedAt($value)
 */
	class TrainerClientsMeetingData extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property string $phone
 * @property string $nic
 * @property string $gender
 * @property string $birthday
 * @property string $address
 * @property string $country_code
 * @property string $avatar_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $currency
 * @property float|null $rating
 * @property int|null $stars_count
 * @property int|null $rating_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatarUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRatingCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStarsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\Authenticatable, \Illuminate\Contracts\Auth\Access\Authorizable {}
}

