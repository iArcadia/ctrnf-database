# ctrnf-database

A repository with Crash Team Racing: Nitro-Fueled relative data.

## Introduction

This repository contains CTR:NF data in JSON-formatted files. These files are located in the `/data` directory.

It also has CTR:NF asset files (like icons) within the `/assets` directory.

## Common structure

All files follow a structure that allows them to be homogenic and to have relationships between them.

The root of the files is an array of the corresponding resource.

Many properties are very common, here is the list:

- `slug: string` is the string identifier for the resource
- `name: object` stores the resource name in different languages. Its keys need to follow the **ISO 639-1** standard.
- `assets: object` stores all asset file information about the resource. This property will be detailed later.
- `relationships: object` An object which stocks all existing relationships of the resource. This property will be detailed later.

A simple example with the *Crash Cove* track with english and french translations for its name:

```json
{
    "slug": "crash-cove",
    "name": {
        "en": "Crash Cove",
        "fr": "Crique Crash"
    }
}
```

### Asset objects

The asset objects store data about the asset files linked to the resource.

- `type: string` is the type of the asset file (as `image/png` for an image).
- `file: string` is the path of the asset file from the root directory.

### Relationship objects

As said, the relationship objects contain the existing relationships for a given resource.

Their properties are ruled like the following:

- `file: string` From which file the linked resource is, from the project root.
- The second property could be one of the next two:
    - `slug: string` is the identifier of the linked resource.
    - `slugs: array<string>` is a list of identifiers when there are many same-typed linked resources.

Taking the previous example of *Crash Cove* and adding *Roo's Tubes*, which are both from the *N. Sanity Beach* area:

```json
{
    "slug": "n-sanity-beach",
    "name": {
        "en": "N. Sanity Beach",
        "fr": "N. Sanity Beach"
    },
    "relationships": {
        "tracks": {
            "file": "\\data\\tracks\\tracks.json",
            "slugs": [
                "crash-cove",
                "roo-s-tubes"
            ]
        }
    }
}
```

```json
{
    "slug": "crash-cove",
    "name": {
        "en": "Crash Cove",
        "fr": "Crique Crash"
    },
    "relationships": {
        "area": {
            "file": "\\data\\areas\\areas.json",
            "slug": "n-sanity-beach"
        }
    }
}
```

```json
{
    "slug": "roo-s-tubes",
    "name": {
        "en": "Roo's Tubes",
        "fr": "Tubes Roo"
    },
    "relationships": {
        "area": {
            "file": "\\data\\areas\\areas.json",
            "slug": "n-sanity-beach"
        }
    }
}
```

Some relationship objects might have a complementary attribute in order to bring more information.

For example, a skin could be purchasable in the Pit Shop with an amount of 1500 Wumpa Coins:

```json
{
    "slug": "my-skin",
    "name": {
        "en": "My skin",
        "fr": "Mon skin"
    },
    "relationships": {
        "way-to-unlock": {
            "file": "\\data\\way-to-unlock\\way-to-unlock.json",
            "slug": "buy-from-the-pit-shop",
            "price":  1500
        }
    }
}
```

Wait, in reality, this skin is also unlockable from the Nitro bar of the Back'n'Time Grand Prix once you attain 1500 points:

```json
{
    "slug": "my-skin",
    "name": {
        "en": "My skin",
        "fr": "Mon skin"
    },
    "relationships": {
        "way-to-unlock": {
            "file": "\\data\\way-to-unlock\\way-to-unlock.json",
            "slugs": [
                {
                    "slug": "buy-from-the-pit-shop",
                    "price": 1500
                },
                {
                    "slug": "grand-prix-nitro-bar",
                    "points": 1500,
                    "relationships": {
                        "grand-prix": {
                            "file": "\\data\\grand-prix\\grand-prix.json",
                            "slug": "back-n-time"
                        }
                    }
                }
            ]
        }
    }
}
```

## All resource structures

### Areas

#### Relationships

- `tracks: array<Track>` stores the tracks of the area
- `arenas: array<Arena>` stores the arenas of the area
- `adventure-mode-boss: Character` is the boss character of the area

### Tracks

#### Properties

- `wumpa-coin-payouts: object` stores Wumpa Coin payouts
    - `1st: integer` is the number of Wumpa Coins earned by finishing this track in 1st position
    - `2nd: integer` is the number of Wumpa Coins earned by finishing this track in 2nd position
    - `3rd: integer` is the number of Wumpa Coins earned by finishing this track in 3rd position
    - `default: integer` is the default number of Wumpa Coins earned by finishing this track (below the 3rd position)
- `time-trial: object` stores Time Trial mode related data
    - `n-tropy-time: integer` is the time made by N. Tropy in milliseconds
    - `nitros-oxide-time: integer` is the time made by Nitros Oxide in milliseconds
    - `game-leaderboard-api-url: object` stores the in-game online leaderboard API URLs
        - `playstation-4: string?` is the API URL for PS4
        - `xbox-one: string?` is the API URL for Xbox One
        - `nintendo-switch: string?` is the API URL for Switch
- `relic-race: object` stores Relic Race mode related data
    - `time-crates: integer` Number of time-freezing crates
    - `easy-mode: object` stores the relic times of the easy mode
        - `sapphire-time: integer` is the time needed to earn the sapphire relic, in milliseconds
        - `gold-time: integer` is the time needed to earn the gold relic, in milliseconds
        - `platinum-time: integer` is the time needed to earn the platinum relic, in milliseconds
    - `normal-mode: object` stores the relic times of the normal and hard mode
        - `sapphire-time: integer` is the time needed to earn the sapphire relic, in milliseconds
        - `gold-time: integer` is the time needed to earn the gold relic, in milliseconds
        - `platinum-time: integer` is the time needed to earn the platinum relic, in milliseconds
    - `game-leaderboard-api-url: object` stores the in-game online leaderboard API URLs
        - `playstation-4: string?` is the API URL for PS4
        - `xbox-one: string?` is the API URL for Xbox One
        - `nintendo-switch: string?` is the API URL for Switch
        
#### Relationships

- `area: Area` is the area from the track is

### Arenas

#### Properties

- `crystal-challenge: object` stores information about the crystal challenge
    - `easy-mode: integer` is time needed to succeed the challenge in easy mode
    - `normal-mode: integer` is time needed to succeed the challenge in normal mode
    - `hard-mode: integer` is time needed to succeed the challenge in hard mode
        
#### Relationships

- `area: Area` is the area from the arena is
        
### Driving styles

#### Properties

- `statistics: object` stores statistic data
    - `speed: integer` is the speed statistic
    - `acceleration: integer` is the acceleration statistic
    - `turn: integer` is the turn statistic
    
#### Relationships

- `characters: array<Character>` stores the characters using the driving style as default
    
### Masks

#### Relationships

- `characters: array<Character>` stores the characters using the mask
- `tracks: array<Track>` stores the tracks using this mask

### Rarities

- `color: object` stores information about the color of the rarity
    - `rgb: object` stores information about RGB values of the color
        - `red: integer` is the red value between 0 and 255
        - `green: integer` is the green value between 0 and 255
        - `blue: integer` is the blue value between 0 and 255
    - `hexadecimal: string` is the hexadecimal notation of the color
    
#### Relationships

- `characters: array<Character>` stores all the characters which have this rarity

### Characters

#### Relationships

- `rarity: Rarity` is the rarity of the character
- `driving-style: Driving Style` is the default driving style used as default by the character
- `masks: array<Mask>` stores the masks used by the character.

### Grand Prix

#### Properties

- `duration: object` stores the dates representing the period
    - `start-date: string` is the starting date of the GP in the format `Y-m-d`
    - `end-date: string` is the ending date of the GP in the format `Y-m-d`

#### Relationships

- `new-track: Track` is the new track brought by the Grand Prix
- `new-characters: array<Character>` stores the new characters brought by the Grand Prix