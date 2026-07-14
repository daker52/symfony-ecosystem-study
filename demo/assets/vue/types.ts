/** Typ odpovědi z Symfony API — den 8 */
export interface StudyTopicDto {
  slug: string;
  title: string;
  body: string;
  day: number;
}

export interface StudyTopicsResponse {
  topics: StudyTopicDto[];
  count: number;
}
